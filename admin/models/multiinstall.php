<?php
/**
* Copyright (C) 2010  Chris Taylor www.forgetso.com (cjtaylor38@gmail.com)
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/
// no direct access
defined('_JEXEC') or die(';)');
jimport('joomla.application.component.model');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
/**
* Settings Model
*
* @package    MultiInstall
* @subpackage Models
*/
class MultiInstallModelMultiInstall extends JModelLegacy {
    function upload($p_file = 'null') {
        set_time_limit(0);
        $folder = JPATH_SITE.'/tmp/multiinstall';
        if(JFolder::exists($folder)) {
            JFolder::delete($folder);
        }
        JFolder::create($folder);
        if($p_file=='null') {
            $extfile = JRequest :: getVar('extfile', null, 'files', 'array');
            $filename = JFile::makeSafe($extfile['name']);
            $src = $extfile['tmp_name'];
            $dest = $folder . '/' . $filename;
            JFile::upload($src, $dest);
        } else {
            $extfile = array();
            $src = JPATH_SITE.'/tmp/'.$p_file;
            $dest = $folder . '/'. $p_file;
            JFile::copy($src, $dest);
            JFile::delete($src);
        }

        $link = 'index.php?option=com_multiinstall';
        $mainframe = JFactory::getApplication();

        if(class_exists('ZipArchive')) {
            $zip = new ZipArchive;
            if ($zip->open($dest) === TRUE) {
                $zip->extractTo($folder);
                $zip->close();
                JFile::delete($dest);
                $pkgs = JFolder::files($folder);
                $this->installExtensions($pkgs);
            }
            else {
                var_dump($zip->open($dest));die;
                $msg = JText::_('UNZIPERROR');
                $mainframe->redirect($link, $msg);
            }
        }
        else {
            $msg = JText::_('ERRORZIPCLASS');
            $mainframe->redirect($link, $msg);
        }
    }

    function installExtensions($pkgs) {
        jimport('joomla.installer.helper');
        jimport('joomla.installer.installer');
        $installer = new JInstaller();
        $installer->setOverwrite(true);
        $pkg_path = JPATH_SITE.'/tmp/multiinstall/';
        $mainframe = JFactory :: getApplication();
        foreach ($pkgs as $pkg) {
            $package = JInstallerHelper :: unpack($pkg_path . $pkg);
            if ($installer->install($package['dir'])) {
                $msg = 1;
            } else {
                $msg = 0;
                $msgtext = JText::sprintf("INSTALLERROR", $pkg);
            }
            if (!$msg) {
                $mainframe->redirect('index.php?option=com_multiinstall', $msgtext);
            }
        }
        if (JFolder :: exists($pkg_path)) {
            JFolder :: delete($pkg_path);
        }
        $msg = JText::sprintf('INSTALLSUCCESS',count($pkgs));
        $mainframe->redirect('index.php?option=com_multiinstall', $msg);
    }

    function getPackageFromUrl()
	{
        $mainframe = JFactory::getApplication();
		// Get a database connector
		$db = JFactory::getDBO();

		// Get the URL of the package to install
		$url = JRequest::getString('install_url');

		// Did you give us a URL?
		if (!$url) {
			$error = JText::_('Please enter a URL');
            $mainframe->redirect('index.php?option=com_multiinstall',$error, 'error');
		}

		// Download the package at the URL given
        jimport('joomla.installer.helper');
		$p_file = JInstallerHelper::downloadPackage($url);

		// Was the package downloaded?
		if (!$p_file) {
			$error = JText::_('Invalid URL');
			$mainframe->redirect('index.php?option=com_multiinstall',$error, 'error');
		}
        $this->upload($p_file);
	}
}