<?php

namespace BestIt\Commercetools\FilterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration class for this bundle.
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage DependencyInjection
 * @version    $id$
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Parses the config.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $builder->root('best_it_commercetools_filter')
            ->children()
            ->scalarNode('translation_domain')
            ->info('Used translation domain (default "messages")')
            ->defaultValue('messages')
            ->end()
            ->scalarNode('product_normalizer_id')
            ->info('Product normalizer (service id / implement interface)')
            ->defaultValue('best_it_commercetools_filter.normalizer.empty_product_normalizer')
            ->end()
            ->scalarNode('client_id')
            ->info('Client service id of commerce tools sdk client')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('config_provider_id')
            ->info('Optional facets factory config provider (service id / implement interface)')
            ->defaultValue('best_it_commercetools_filter.provider.empty_facet_config_provider')
            ->end()
            ->scalarNode('url_generator_id')
            ->info('Generator for filter urls')
            ->defaultValue('best_it_commercetools_filter.generator.default_filter_url_generator')
            ->end()
            ->scalarNode('cache_life_time')
            ->info('Cache life time. Enum Attribute labels are cached to minimize CommerceTools requests.')
            ->defaultValue(86400)
            ->end()
            ->end()
            ->append($this->getSortingNode())
            ->append($this->getPaginationNode())
            ->append($this->getViewNode())
            ->append($this->getFacetsNode());

        return $builder;
    }

    /**
     * Add the config for view
     *
     * @return ArrayNodeDefinition
     */
    private function getViewNode(): ArrayNodeDefinition
    {
        $node = (new TreeBuilder())->root('view');

        $node
            ->info('View settings')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('default')
            ->info('Default view type (eg. grid, list)')
            ->defaultValue('list')
            ->end()
            ->end();

        return $node;
    }

    /**
     * Add the config for facets
     *
     * @return ArrayNodeDefinition
     */
    private function getFacetsNode(): ArrayNodeDefinition
    {
        $node = (new TreeBuilder())->root('facet');

        $node
            ->info('Facet config')
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
            ->end();

        return $node;
    }

    /**
     * Add the config for pagination
     *
     * @return ArrayNodeDefinition
     */
    private function getPaginationNode(): ArrayNodeDefinition
    {
        $node = (new TreeBuilder())->root('pagination');

        $node
            ->info('Pagination settings')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('products_per_page')
            ->info('Products per page')
            ->defaultValue(20)
            ->end()
            ->scalarNode('neighbours')
            ->info('Neighbours at pagination 1 => "1 2 3" | 2 => "1 2 3 4 5"')
            ->defaultValue(1)
            ->end()
            ->end();

        return $node;
    }

    /**
     * Add the config for sorting
     *
     * @return ArrayNodeDefinition
     */
    private function getSortingNode(): ArrayNodeDefinition
    {
        $node = (new TreeBuilder())->root('sorting');

        $node
            ->isRequired()
            ->children()
            ->scalarNode('default')
            ->info('The default sort')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->arrayNode('choices')
            ->info('Define the sorting id, the translation key and sort query. This is an array with all available sortings.')
            ->normalizeKeys(false)
            ->requiresAtLeastOneElement()
            ->isRequired()
            ->useAttributeAsKey('key')
            ->prototype('array')
            ->children()
            ->scalarNode('query')
            ->info('Api query for sdk')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('translation')
            ->info('Translation key')
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
