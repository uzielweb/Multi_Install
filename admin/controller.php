<?php
/**
 * Copyright (C) 2010  Chris Taylor (cjtaylor38@gmail.com)
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
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.controller' );

/**
 * Multi Install Controller
 *
 * @package Joomla
 * @subpackage multiinstall
 */
class MultiInstallController extends JControllerLegacy {
    /**
     * Constructor
     * @access private
     * @subpackage multiinstall
     */
	public function display($cachable = false, $urlparams = false)
	{
		parent::display();
	}// function

    /**
     * Uploader
     * @access private
     * @subpackage multiinstall
     */
	function upload()
	{
		$model = $this->getModel();
        $model->upload();
	}// function

    /**
     * Downloader
     * @access private
     * @subpackage multiinstall
     */
	function url()
	{
		$model = $this->getModel();
        $model->getPackageFromUrl();
	}// function

}// class

?>