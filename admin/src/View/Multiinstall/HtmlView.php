<?php
/**
 * @package     MultiInstall
 * @subpackage  com_multiinstall
 *
 * @copyright   (C) 2010 Chris Taylor, (C) 2026 Uziel Almeida Oliveira
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Uzielweb\Component\Multiinstall\Administrator\View\Multiinstall;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * Multiinstall Html View
 */
class HtmlView extends BaseHtmlView
{
    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse.
     *
     * @return  void
     */
    public function display($tpl = null): void
    {
        $this->addToolbar();

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     */
    protected function addToolbar(): void
    {
        ToolbarHelper::title(Text::_('COM_MULTIINSTALL'), 'upload');

        $user = Factory::getApplication()->getIdentity();

        if ($user && $user->authorise('core.admin', 'com_multiinstall')) {
            ToolbarHelper::preferences('com_multiinstall');
        }
    }
}
