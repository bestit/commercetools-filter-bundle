<?php

namespace BestIt\Commercetools\FilterBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Loads the config for the filter bundle.
 * @author chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage DependencyInjection
 * @version $id$
 */
class FilterExtension extends Extension
{
    /**
     * @inheritdoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $container->setParameter('best_it_commercetools_filter.config', $config ?? []);

        // Set alias ervices
        $container->setAlias('best_it_commercetools_filter.normalizer.product', $config['product_normalizer']);
        $container->setAlias('best_it_commercetools_filter.request.client', $config['client']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }
}
