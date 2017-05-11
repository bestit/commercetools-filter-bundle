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
                ->scalarNode('translation_domain')
                    ->info('Used translation domain for all this bundle')
                    ->defaultValue('messages')
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
            ->end()
            ->append($this->getSortingNode())
            ->append($this->getPaginationNode())
            ->append($this->getViewNode())
            ->append($this->getFacetsNode())
        ;

        return $builder;
    }

    /**
     * Add the config for view
     * @return ArrayNodeDefinition
     */
    private function getViewNode(): ArrayNodeDefinition
    {
        $node = (new TreeBuilder())->root('view');

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('query_key')
                    ->info('The view key for sorting')
                    ->defaultValue('view')
                ->end()
                ->scalarNode('default')
                    ->info('Default view type (eg. grid, list)')
                    ->defaultValue('list')
                ->end()
            ->end()
        ;

        return $node;
    }

    /**
     * Add the config for facets
     * @return ArrayNodeDefinition
     */
    private function getFacetsNode(): ArrayNodeDefinition
    {
        $node = (new TreeBuilder())->root('facet');

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('reset')
                    ->info('Translation key for reset button or false for disable reset button')
                    ->defaultValue('reset')
                ->end()
                ->scalarNode('submit')
                    ->info('Translation key for reset button or false for disable submit button')
                    ->defaultValue('submit')
                ->end()
            ->end()
        ;

        return $node;
    }

    /**
     * Add the config for pagination
     * @return ArrayNodeDefinition
     */
    private function getPaginationNode(): ArrayNodeDefinition
    {
        $node = (new TreeBuilder())->root('pagination');

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('products_per_page')
                    ->info('Products per page')
                    ->defaultValue(20)
                ->end()
                ->scalarNode('neighbours')
                    ->info('Neighbours at pagination')
                    ->defaultValue(1)
                ->end()
                ->scalarNode('query_key')
                    ->info('The query key for current page')
                    ->defaultValue('page')
                ->end()
            ->end()
        ;

        return $node;
    }

    /**
     * Add the config for sorting
     * @return ArrayNodeDefinition
     */
    private function getSortingNode(): ArrayNodeDefinition
    {
        $node = (new TreeBuilder())->root('sorting');

        $node
            ->isRequired()
            ->children()
                ->scalarNode('query_key')
                    ->info('The query key for sorting')
                    ->defaultValue('sort')
                ->end()
                ->scalarNode('default')
                    ->info('The default sort')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('choices')
                    ->info('Define the sorting id, the translation key and sort query')
                    ->normalizeKeys(false)
                    ->requiresAtLeastOneElement()
                    ->isRequired()
                    ->useAttributeAsKey('key')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('query')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('translation')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $node;
    }
}
