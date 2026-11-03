<?php
/**
 * @package     MultiInstall
 * @subpackage  com_multiinstall
 *
 * @copyright   (C) 2010 Chris Taylor, (C) 2026 Uziel Almeida Oliveira
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Uzielweb\Component\Multiinstall\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

/**
 * Multiinstall master display controller.
 */
class DisplayController extends BaseController
{
    /**
     * Default view for the component.
     *
     * @var    string
     */
    protected $default_view = 'multiinstall';

    /**
     * Method to display a view.
     *
     * @param   boolean  $cachable   If true, the view output will be cached.
     * @param   array    $urlparams  An array of safe URL parameters and their variable types.
     *
     * @return  static
     */
    public function display($cachable = false, $urlparams = [])
    {
        return parent::display($cachable, $urlparams);
    }

    /**
     * Handles file upload installation.
     *
     * @return  void
     */
    public function upload(): void
    {
        $this->checkToken();

        /** @var \Uzielweb\Component\Multiinstall\Administrator\Model\MultiinstallModel $model */
        $model = $this->getModel('Multiinstall');

        $result = $model->upload();

        $this->setRedirect(
            Route::_('index.php?option=com_multiinstall', false),
            $result['message'],
            $result['success'] ? 'message' : 'error'
        );
    }

    /**
     * Handles installation from URL.
     *
     * @return  void
     */
    public function url(): void
    {
        $this->checkToken();

        $url = $this->input->getString('install_url', '');

        /** @var \Uzielweb\Component\Multiinstall\Administrator\Model\MultiinstallModel $model */
        $model = $this->getModel('Multiinstall');

        $result = $model->installFromUrl($url);

        $this->setRedirect(
            Route::_('index.php?option=com_multiinstall', false),
            $result['message'],
            $result['success'] ? 'message' : 'error'
        );
    }
}
