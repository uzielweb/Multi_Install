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
?>

<form action="index.php?option=com_multiinstall"
	enctype="multipart/form-data" method="post" name="uploadForm">
<table class="admintable">
    <tr>
        <td>
            <?php echo JText::_('INSTRUCTIONS'); ?>
        </td>
    </tr>
	<tr>
		<td>
		<input type="file" name="extfile"
			title="<?php echo JText::_('TIPEXTFILE'); ?>" class="hasTip" />
        <input type="submit" value="<?php echo JText::_('SUBMIT');?>"
			onclick="document.uploadForm.submit( );" />
		</td>
	</tr>
</table>
<input type="hidden" name="option" value="com_multiinstall" />
<input type="hidden" name="controller" value="multiinstall" />
<input type="hidden" name="task" value="upload" />
</form>

<form action="index.php?option=com_multiinstall"
	enctype="multipart/form-data" method="post" name="urlForm">
<table class="admintable">
    <tr>
        <td>
            <?php echo JText::_('URLINSTRUCTIONS'); ?>
        </td>
    </tr>
	<tr>
        <td>
			<input type="text" value="http://" size="70" class="input_box" name="install_url" id="install_url">
			<input type="submit" onclick="document.urlForm.submit( )" value="Install" class="button">
		</td>
	</tr>
</table>
<input type="hidden" name="option" value="com_multiinstall" />
<input type="hidden" name="controller" value="multiinstall" />
<input type="hidden" name="task" value="url" />
</form>
<table class="admintable">
    <tr>
        <td>
            <?php echo JText::_('CREDITS'); ?>
        </td>
    </tr>
</table>