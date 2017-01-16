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

// load language files. include en-GB as fallback
$jlang =JFactory::getLanguage();
$jlang->load('com_multiinstall', JPATH_ADMINISTRATOR, 'en-GB', true);
$jlang->load('com_multiinstall', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
$jlang->load('com_multiinstall', JPATH_ADMINISTRATOR, null, true);

// Require the base controller
require_once JPATH_ADMINISTRATOR.'/components/com_multiinstall/controller.php';

$doc = JFactory::getDocument();
$logo = JURI::base().'/components/com_multiinstall/images/installer.png';
$doc->addStyleSheet('.icon-48-generic{background-image:url("'.$logo.'") !important}');  

$controller = JRequest::getVar('controller');

// Require specific controller if requested
if( $controller )
{
   $path = JPATH_ADMINISTRATOR.'/controllers/'.$controller.'.php';
   if( file_exists($path))
   {
      require_once $path;
   } else
   {
      $controller = '';
   }
}


// Create the controller
$classname    = 'MultiInstallController'.$controller;
$controller   = new $classname( );

// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();


?>