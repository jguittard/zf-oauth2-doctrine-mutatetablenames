<?php

namespace ZF\OAuth2\Doctrine\MutateTableNames;

use Doctrine\Common\EventManager;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature;
use ZF\OAuth2\Doctrine\MutateTableNames\EventListener\MutateTableNamesSubscriber;

class Module implements
    Feature\AutoloaderProviderInterface,
    Feature\BootstrapListenerInterface,
    Feature\ConfigProviderInterface,
    Feature\DependencyIndicatorInterface
{

    /**
     * Retrieve autoloader configuration
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/',
                )
            )
        );
    }

    /**
     * Attaches an doctrine event subscriber to the configured event_manager
     *
     * @inheritdoc
     *
     * @param EventInterface $e
     */
    public function onBootstrap(EventInterface $e)
    {
        $serviceLocator = $e->getParam('application')->getServiceManager();
        $config         = $serviceLocator->get('Config');

        /** @var MutateTableNamesSubscriber $subscriber */
        $subscriber = $serviceLocator->get('ZF\OAuth2\Doctrine\MutateTableNames\MutateTableNamesSubscriber');

        /** @var EventManager $eventManager */
        $eventManager = $serviceLocator->get($config['zf-oauth2-doctrine']['storage_settings']['event_manager']);
        $eventManager->addEventSubscriber($subscriber);
    }

    /**
     * @inheritdoc
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @inheritdoc
     */
    public function getModuleDependencies()
    {
        return ['ZF\OAuth2\Doctrine'];
    }

}
