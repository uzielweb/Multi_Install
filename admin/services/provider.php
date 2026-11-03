<?php
/**
 * @package     MultiInstall
 * @subpackage  com_multiinstall
 *
 * @copyright   (C) 2010 Chris Taylor, (C) 2026 Uziel Almeida Oliveira
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Uzielweb\Component\Multiinstall\Administrator\Extension\MultiinstallComponent;

return new class () implements ServiceProviderInterface {
    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  void
     */
    public function register(Container $container): void
    {
        $container->registerServiceProvider(new MVCFactory('\\Uzielweb\\Component\\Multiinstall'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\Uzielweb\\Component\\Multiinstall'));

        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new MultiinstallComponent(
                    $container->get(ComponentDispatcherFactoryInterface::class)
                );

                $component->setMVCFactory($container->get(MVCFactoryInterface::class));

                return $component;
            }
        );
    }
};
