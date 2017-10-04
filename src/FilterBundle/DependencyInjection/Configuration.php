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
                        ->info('DEPRECATED! Cache life time. Enum Attribute labels are cached to minimize CommerceTools requests.')
                        ->defaultValue(86400)
                    ->end()
                ->end()
            ->append($this->getSortingNode())
            ->append($this->getPaginationNode())
            ->append($this->getViewNode())
            ->append($this->getSuggestNode())
            ->append($this->getSearchNode())
            ->append($this->getEnumNormalizerNode())
            ->append($this->getCategoryNormalizerNode())
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
     * Add the config for suggest
     *
     * @return ArrayNodeDefinition
     */
    private function getSuggestNode(): ArrayNodeDefinition
    {
        $node = (new TreeBuilder())->root('suggest');

        $node
            ->info('Suggest settings')
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('enable_fuzzy')
                    ->defaultTrue()
                    ->info('Use fuzzy for suggest')
                ->end()
                ->scalarNode('fuzzy_level')
                    ->defaultNull()
                    ->info('Use fuzzy level for suggest')
                    ->validate()
                        ->ifTrue(function ($value) { return !is_int($value) && $value !== null; })
                        ->thenInvalid('Configuration value must be either int or null.')
                    ->end()
                ->end()
                ->booleanNode('match_variants')
                    ->info('Mark matching variants with "isMatchingVariant".')
                    ->defaultFalse()
                ->end()
            ->end();

        return $node;
    }

    /**
     * Add the config for search
     *
     * @return ArrayNodeDefinition
     */
    private function getSearchNode(): ArrayNodeDefinition
    {
        $node = (new TreeBuilder())->root('search');

        $node
            ->info('Search settings')
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('enable_fuzzy')
                    ->defaultTrue()
                    ->info('Use fuzzy for search')
                ->end()
                ->scalarNode('fuzzy_level')
                    ->defaultNull()
                    ->info('Use fuzzy level for search')
                    ->validate()
                        ->ifTrue(function ($value) { return !is_int($value) && $value !== null; })
                        ->thenInvalid('Configuration value must be either int or null.')
                    ->end()
                ->end()
                ->booleanNode('match_variants')
                    ->info('Mark matching variants with "isMatchingVariant".')
                    ->defaultFalse()
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
     * Add the config for enum normalization
     *
     * @return ArrayNodeDefinition
     */
    private function getEnumNormalizerNode(): ArrayNodeDefinition
    {
        $node = (new TreeBuilder())->root('enum_normalizer');

        $node
            ->info('Normalization for enum')
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('enable')
                    ->info('Enable the normalization (default: true)')
                    ->defaultTrue()
                ->end()
                ->scalarNode('normalizer_id')
                    ->info('Optional Service id for own normalizer')
                    ->defaultValue('best_it_commercetools_filter.normalizer_term.enum_attribute_normalizer')
                ->end()
                ->scalarNode('cache_id')
                    ->info('Service id if for cache (default: cache.app)')
                    ->defaultValue('cache.app')
                ->end()
                ->integerNode('cache_life_time')
                    ->info('Cache life time. Enum Attribute labels are cached to minimize CommerceTools requests.')
                    ->defaultValue(86400)
                ->end()
            ->end();

        return $node;
    }

    /**
     * Add the config for category normalization
     *
     * @return ArrayNodeDefinition
     */
    private function getCategoryNormalizerNode(): ArrayNodeDefinition
    {
        $node = (new TreeBuilder())->root('category_normalizer');

        $node
            ->info('Normalization for categories name')
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('enable')
                    ->info('Enable the normalization (default: true)')
                    ->defaultTrue()
                ->end()
                ->scalarNode('normalizer_id')
                    ->info('Optional service id for own normalizer')
                    ->defaultValue('best_it_commercetools_filter.normalizer_term.category_normalizer')
                ->end()
                ->scalarNode('cache_id')
                    ->info('Service id if for cache (default: cache.app)')
                    ->defaultValue('cache.app')
                ->end()
                ->integerNode('cache_life_time')
                    ->info('Cache life time. Categories labels are cached to minimize CommerceTools requests.')
                    ->defaultValue(86400)
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
                                ->info('Api query for sdk (default: null for relevance sorting) ')
                                ->defaultNull()
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
