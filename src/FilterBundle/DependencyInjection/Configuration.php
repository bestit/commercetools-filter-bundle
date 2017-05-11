<?php

namespace BestIt\Commercetools\FilterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration class for this bundle.
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage DependencyInjection
 * @version $id$
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Parses the config.
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $builder->root('best_it_commercetools_filter')
            ->children()
                ->append($this->getSortingNode())
                ->scalarNode('products_per_page')
                    ->info('Products per page')
                    ->defaultValue(20)
                ->end()
                ->scalarNode('neighbours')
                    ->info('Neighbours at pagination')
                    ->defaultValue(1)
                ->end()
                ->scalarNode('page_query_key')
                    ->info('The query key for current page')
                    ->defaultValue('page')
                ->end()
                ->scalarNode('sort_query_key')
                    ->info('The query key for sorting')
                    ->defaultValue('sort')
                ->end()
                ->scalarNode('view_query_key')
                    ->info('The view key for sorting')
                    ->defaultValue('view')
                ->end()
                ->scalarNode('default_view')
                    ->info('Default view type (eg. grid, list)')
                    ->defaultValue('list')
                ->end()
                ->scalarNode('product_normalizer_id')
                    ->info('Used product normalizer')
                    ->defaultValue('best_it_commercetools_filter.normalizer.empty_product_normalizer')
                ->end()
                ->scalarNode('client_id')
                    ->info('Used commerce tools client')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('config_provider_id')
                    ->info('Used config provider')
                    ->defaultValue('best_it_commercetools_filter.factory.facet_config_collection_factory')
                ->end()
            ->end();

        return $builder;
    }

    /**
     * Add the config for sorting
     * @return ArrayNodeDefinition
     */
    private function getSortingNode(): ArrayNodeDefinition
    {
        $node = (new TreeBuilder())->root('sorting');

        $node
                ->info('Define the sorting id, the translation key and sort query')
                ->normalizeKeys(false)
                ->requiresAtLeastOneElement()
                ->isRequired()
                ->useAttributeAsKey('key')
                ->prototype('array')
                    ->children()
                        ->scalarNode('query')->isRequired()->end()
                        ->scalarNode('translation')->isRequired()->end()
                        ->booleanNode('default')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end();

        return $node;
    }
}
