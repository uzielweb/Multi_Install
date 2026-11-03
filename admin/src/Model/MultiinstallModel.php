<?php
/**
 * @package     MultiInstall
 * @subpackage  com_multiinstall
 *
 * @copyright   (C) 2010 Chris Taylor, (C) 2026 Uziel Almeida Oliveira
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Uzielweb\Component\Multiinstall\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\Archive\Archive;
use Joomla\CMS\Factory;
use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Installer\InstallerHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Database\DatabaseInterface;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use Joomla\Filesystem\Path;

/**
 * Multiinstall Model
 */
class MultiinstallModel extends BaseDatabaseModel
{
    /**
     * Installs all packages contained in an uploaded ZIP archive.
     *
     * @param   array|null  $userfile  Uploaded file data ($_FILES entry).
     *
     * @return  array  ['success' => bool, 'message' => string]
     */
    public function installFromUpload(?array $userfile): array
    {
        if (empty($userfile['name']) || empty($userfile['tmp_name']) || (int) ($userfile['error'] ?? 1) !== 0) {
            return $this->result(false, Text::_('COM_MULTIINSTALL_NO_FILE_SELECTED'));
        }

        if (strtolower(File::getExt($userfile['name'])) !== 'zip') {
            return $this->result(false, Text::_('COM_MULTIINSTALL_UNZIP_ERROR'));
        }

        $workDir = $this->createWorkDir();
        $archive = Path::clean($workDir . '/' . File::makeSafe($userfile['name']));

        try {
            File::upload($userfile['tmp_name'], $archive);
        } catch (\Throwable $e) {
            $this->removeDir($workDir);

            return $this->result(false, Text::_('COM_MULTIINSTALL_UNZIP_ERROR'));
        }

        return $this->processArchive($archive, $workDir);
    }

    /**
     * Downloads a ZIP archive from a URL and installs all packages inside it.
     *
     * @param   string  $url  Package URL.
     *
     * @return  array  ['success' => bool, 'message' => string]
     */
    public function installFromUrl(string $url): array
    {
        $url = trim($url);

        if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL) || !preg_match('#^https?://#i', $url)) {
            return $this->result(false, Text::_('COM_MULTIINSTALL_PLEASE_ENTER_URL'));
        }

        $tmpPath    = Factory::getApplication()->get('tmp_path');
        $downloaded = InstallerHelper::downloadPackage($url);

        if (!$downloaded) {
            return $this->result(false, Text::_('COM_MULTIINSTALL_INVALID_URL'));
        }

        $workDir = $this->createWorkDir();
        $archive = Path::clean($workDir . '/' . basename($downloaded));

        try {
            File::move(Path::clean($tmpPath . '/' . $downloaded), $archive);
        } catch (\Throwable $e) {
            $this->removeDir($workDir);

            return $this->result(false, Text::_('COM_MULTIINSTALL_INVALID_URL'));
        }

        return $this->processArchive($archive, $workDir);
    }

    /**
     * Extracts the outer archive and installs each inner package.
     *
     * @param   string  $archive  Full path of the outer ZIP archive.
     * @param   string  $workDir  Temporary working directory (removed at the end).
     *
     * @return  array
     */
    protected function processArchive(string $archive, string $workDir): array
    {
        $extractDir = Path::clean($workDir . '/extracted');

        // Same archive library used by the core installer (native ZipArchive with pure-PHP fallback).
        try {
            $extracted = (new Archive(['tmp_path' => Factory::getApplication()->get('tmp_path')]))
                ->extract($archive, $extractDir);
        } catch (\Throwable $e) {
            $extracted = false;
        }

        if (!$extracted) {
            $this->removeDir($workDir);

            return $this->result(false, Text::_('COM_MULTIINSTALL_UNZIP_ERROR'));
        }

        $packages = Folder::files($extractDir, '\.(zip|tar|tgz|gz|bz2|tbz2)$', false, true);

        if (empty($packages)) {
            $this->removeDir($workDir);

            return $this->result(false, Text::_('COM_MULTIINSTALL_NO_PACKAGES_FOUND'));
        }

        sort($packages);

        $result = $this->installPackages($packages);
        $this->removeDir($workDir);

        return $result;
    }

    /**
     * Installs each package sequentially, stopping at the first failure.
     *
     * @param   string[]  $packages  Full paths of the package files.
     *
     * @return  array
     */
    protected function installPackages(array $packages): array
    {
        $db        = Factory::getContainer()->get(DatabaseInterface::class);
        $installed = 0;

        foreach ($packages as $packageFile) {
            $package = InstallerHelper::unpack($packageFile, true);

            if (empty($package['dir']) || empty($package['type'])) {
                if (!empty($package['extractdir'])) {
                    InstallerHelper::cleanupInstall('', $package['extractdir']);
                }

                return $this->failure($installed, basename($packageFile));
            }

            // A fresh installer per package avoids state leaking between installs.
            $installer = new Installer();
            $installer->setDatabase($db);
            $installer->setOverwrite(true);

            $ok = $installer->install($package['dir']);

            InstallerHelper::cleanupInstall('', $package['extractdir']);

            if (!$ok) {
                return $this->failure($installed, basename($packageFile));
            }

            $installed++;
        }

        return $this->result(true, Text::sprintf('COM_MULTIINSTALL_INSTALL_SUCCESS', $installed));
    }

    /**
     * Builds a failure result, reporting how many packages were installed before it.
     *
     * @param   int     $installed  Number of packages installed before the failure.
     * @param   string  $package    Name of the failed package.
     *
     * @return  array
     */
    protected function failure(int $installed, string $package): array
    {
        $message = Text::sprintf('COM_MULTIINSTALL_INSTALL_ERROR', $package);

        if ($installed > 0) {
            $message .= ' ' . Text::sprintf('COM_MULTIINSTALL_INSTALL_PARTIAL', $installed);
        }

        return $this->result(false, $message);
    }

    /**
     * Creates a unique temporary working directory inside the site tmp path.
     *
     * @return  string
     */
    protected function createWorkDir(): string
    {
        $dir = Path::clean(Factory::getApplication()->get('tmp_path') . '/multiinstall_' . bin2hex(random_bytes(8)));
        Folder::create($dir);

        return $dir;
    }

    /**
     * Removes a directory, ignoring errors.
     *
     * @param   string  $dir  Directory path.
     *
     * @return  void
     */
    protected function removeDir(string $dir): void
    {
        try {
            if (is_dir($dir)) {
                Folder::delete($dir);
            }
        } catch (\Throwable $e) {
            // Ignore cleanup failures.
        }
    }

    /**
     * Builds a result array.
     *
     * @param   bool    $success  Whether the operation succeeded.
     * @param   string  $message  Message to display.
     *
     * @return  array
     */
    protected function result(bool $success, string $message): array
    {
        return ['success' => $success, 'message' => $message];
    }
}
