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
     * Installs all packages contained in an uploaded ZIP archive.
     *
     * @return  void
     */
    public function upload(): void
    {
        $this->checkToken();
        $this->assertCanInstall();

        $result = $this->getModel('Multiinstall')
            ->installFromUpload($this->input->files->get('extfile', null, 'raw'));

        $this->redirectWith($result);
    }

    /**
     * Downloads a ZIP archive from a URL and installs all packages inside it.
     *
     * @return  void
     */
    public function url(): void
    {
        $this->checkToken();
        $this->assertCanInstall();

        $result = $this->getModel('Multiinstall')
            ->installFromUrl($this->input->post->getString('install_url', ''));

        $this->redirectWith($result);
    }

    /**
     * Installing extensions requires the same permission as the core installer.
     *
     * @return  void
     *
     * @throws  \RuntimeException
     */
    protected function assertCanInstall(): void
    {
        if (!$this->app->getIdentity()->authorise('core.manage', 'com_installer')) {
            throw new \RuntimeException(Text::_('JERROR_ALERTNOAUTHOR'), 403);
        }
    }

    /**
     * Redirects back to the component with the result message.
     *
     * @param   array  $result  ['success' => bool, 'message' => string]
     *
     * @return  void
     */
    protected function redirectWith(array $result): void
    {
        $this->setRedirect(
            Route::_('index.php?option=com_multiinstall', false),
            $result['message'],
            $result['success'] ? 'success' : 'error'
        );
    }
}
