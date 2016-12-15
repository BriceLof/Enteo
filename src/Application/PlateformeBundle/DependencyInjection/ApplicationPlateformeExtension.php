<?php

namespace Application\PlateformeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;


/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class ApplicationPlateformeExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {/**
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
     * 
     */
        
       $config = $this->processConfiguration(new Configuration(), $configs);
       /*var_dump($config
               exit;*/
       $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        
        $loader->load('services.yml');
        if ($container->hasDefinition('application_plateforme')) {
            $definition = $container->getDefinition('google_calendar');
            if (isset($config['google_calendar']['application_name'])) {
                $definition
                    ->addMethodCall('setApplicationName', [$config['google_calendar']['application_name']]);
            }
            if (isset($config['google_calendar']['credentials_path'])) {
                $definition
                    ->addMethodCall('setCredentialsPath', [$config['google_calendar']['credentials_path']]);
            }
            if (isset($config['google_calendar']['client_secret_path'])) {
                $definition
                    ->addMethodCall('setClientSecretPath', [$config['google_calendar']['client_secret_path']]);
            }
        }
    }
}
