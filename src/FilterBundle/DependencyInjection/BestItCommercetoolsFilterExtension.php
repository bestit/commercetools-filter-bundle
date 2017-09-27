<?php

namespace BestIt\Commercetools\FilterBundle\DependencyInjection;

use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfigCollection;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Loads the config for the filter bundle.
 *
 * @author     chowanski <michel.chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage DependencyInjection
 * @version    $id$
 */
class BestItCommercetoolsFilterExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        // Fix deprecated config
        $config['enum_normalizer']['cache_life_time'] = $config['cache_life_time'];

        $container->setParameter('best_it_commercetools_filter.config', $config ?? []);

        // Set alias services
        $container->setAlias('best_it_commercetools_filter.normalizer.product', $config['product_normalizer_id']);
        $container->setAlias('best_it_commercetools_filter.request.client', $config['client_id']);
        $container->setAlias('best_it_commercetools_filter.generator.url', $config['url_generator_id']);
        $container->setAlias(
            'best_it_commercetools_filter.normalizer.category.cache',
            $config['category_normalizer']['cache_id']
        );
        $container->setAlias(
            'best_it_commercetools_filter.normalizer.enum.cache',
            $config['enum_normalizer']['cache_id']
        );
        $container->setAlias(
            'best_it_commercetools_filter.normalizer.enum',
            $config['enum_normalizer']['normalizer_id']
        );
        $container->setAlias(
            'best_it_commercetools_filter.normalizer.category',
            $config['category_normalizer']['normalizer_id']
        );

        // Set parameters
        $container->setParameter(
            'best_it_commercetools_filter.config.enum_normalizer.cache_life_time',
            $config['enum_normalizer']['cache_life_time']
        );
        $container->setParameter(
            'best_it_commercetools_filter.config.category_normalizer.cache_life_time',
            $config['category_normalizer']['cache_life_time']
        );

        // Disable services
        if ($config['enum_normalizer']['enable'] === false) {
            $container->removeDefinition('best_it_commercetools_filter.listener_term.enum_attribute_listener');
        }

        if ($config['category_normalizer']['enable'] === false) {
            $container->removeDefinition('best_it_commercetools_filter.listener_term.category_listener');
        }

        // Set config factory
        $container
            ->register('best_it_commercetools_filter.model.facet_config_collection', FacetConfigCollection::class)
            ->setFactory([new Reference($config['config_provider_id']), 'create']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }
}
