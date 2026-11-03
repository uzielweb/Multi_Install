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

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Installer\InstallerHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use ZipArchive;

/**
 * Multiinstall Model
 */
class MultiinstallModel extends BaseDatabaseModel
{
    /**
     * Upload and install multiple extensions.
     *
     * @param   string|null  $downloadedFile  Filename of an already downloaded package in tmp.
     *
     * @return  array  Result containing 'success' (bool) and 'message' (string).
     */
    public function upload(?string $downloadedFile = null): array
    {
        @set_time_limit(0);

        $app = Factory::getApplication();
        $config = $app->getConfig();
        $tmpDir = $config->get('tmp_path', JPATH_ROOT . '/tmp');

        $workingFolder = Path::clean($tmpDir . '/multiinstall_' . uniqid());

        if (Folder::exists($workingFolder)) {
            Folder::delete($workingFolder);
        }

        if (!Folder::create($workingFolder)) {
            return [
                'success' => false,
                'message' => Text::_('COM_MULTIINSTALL_UNZIP_ERROR'),
            ];
        }

        $destArchive = '';

        if ($downloadedFile === null) {
            $files = $app->getInput()->files->get('extfile', [], 'array');

            if (empty($files) || empty($files['name']) || empty($files['tmp_name'])) {
                Folder::delete($workingFolder);
                return [
                    'success' => false,
                    'message' => Text::_('COM_MULTIINSTALL_NO_FILE_SELECTED'),
                ];
            }

            $safeFileName = File::makeSafe($files['name']);
            $destArchive  = Path::clean($workingFolder . '/' . $safeFileName);

            if (!File::upload($files['tmp_name'], $destArchive, false, true)) {
                Folder::delete($workingFolder);
                return [
                    'success' => false,
                    'message' => Text::_('COM_MULTIINSTALL_UNZIP_ERROR'),
                ];
            }
        } else {
            $sourceFile = Path::clean($tmpDir . '/' . $downloadedFile);
            $destArchive = Path::clean($workingFolder . '/' . $downloadedFile);

            if (!File::exists($sourceFile)) {
                Folder::delete($workingFolder);
                return [
                    'success' => false,
                    'message' => Text::_('COM_MULTIINSTALL_INVALID_URL'),
                ];
            }

            File::copy($sourceFile, $destArchive);
            File::delete($sourceFile);
        }

        if (!class_exists('ZipArchive')) {
            Folder::delete($workingFolder);
            return [
                'success' => false,
                'message' => Text::_('COM_MULTIINSTALL_ERROR_ZIP_CLASS'),
            ];
        }

        $extractTarget = Path::clean($workingFolder . '/extracted');
        Folder::create($extractTarget);

        $zip = new ZipArchive();
        $openResult = $zip->open($destArchive);

        if ($openResult !== true) {
            Folder::delete($workingFolder);
            return [
                'success' => false,
                'message' => Text::_('COM_MULTIINSTALL_UNZIP_ERROR'),
            ];
        }

        $zip->extractTo($extractTarget);
        $zip->close();
        File::delete($destArchive);

        $packages = Folder::files($extractTarget, '\.(zip|tar\.gz|tgz)$', false, true);

        if (empty($packages)) {
            Folder::delete($workingFolder);
            return [
                'success' => false,
                'message' => Text::_('COM_MULTIINSTALL_NO_PACKAGES_FOUND'),
            ];
        }

        return $this->installBatchPackages($packages, $workingFolder);
    }

    /**
     * Installs remote package from URL.
     *
     * @param   string  $url  Package URL
     *
     * @return  array
     */
    public function installFromUrl(string $url): array
    {
        $url = trim($url);

        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return [
                'success' => false,
                'message' => Text::_('COM_MULTIINSTALL_PLEASE_ENTER_URL'),
            ];
        }

        $downloadedFile = InstallerHelper::downloadPackage($url);

        if (!$downloadedFile) {
            return [
                'success' => false,
                'message' => Text::_('COM_MULTIINSTALL_INVALID_URL'),
            ];
        }

        return $this->upload($downloadedFile);
    }

    /**
     * Executes sequential batch installation of packages.
     *
     * @param   array   $packages       Full file paths of packages.
     * @param   string  $workingFolder  Base working directory to cleanup.
     *
     * @return  array
     */
    protected function installBatchPackages(array $packages, string $workingFolder): array
    {
        $installer = Installer::getInstance();
        $installer->setOverwrite(true);

        $installedCount = 0;
        $failedPackage = null;

        foreach ($packages as $pkgFile) {
            $unpacked = InstallerHelper::unpack($pkgFile);

            if (!$unpacked || empty($unpacked['dir'])) {
                $failedPackage = basename($pkgFile);
                break;
            }

            $installOk = $installer->install($unpacked['dir']);

            // Cleanup unpacked files for this specific extension
            InstallerHelper::cleanupInstall($pkgFile, $unpacked['resultdir']);

            if (!$installOk) {
                $failedPackage = basename($pkgFile);
                break;
            }

            $installedCount++;
        }

        // Clean up entire temporary work folder
        if (Folder::exists($workingFolder)) {
            Folder::delete($workingFolder);
        }

        if ($failedPackage !== null) {
            return [
                'success' => false,
                'message' => sprintf(Text::_('COM_MULTIINSTALL_INSTALL_ERROR'), $failedPackage),
            ];
        }

        return [
            'success' => true,
            'message' => sprintf(Text::_('COM_MULTIINSTALL_INSTALL_SUCCESS'), $installedCount),
        ];
    }
}
