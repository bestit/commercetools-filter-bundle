<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Factory;

use BestIt\Commercetools\FilterBundle\Factory\SearchConfigFactory;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test for config factory
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 * @version    $id$
 */
class SearchConfigFactoryTest extends TestCase
{
    /**
     * Test create config
     *
     * @return void
     */
    public function testCreate()
    {
        $sortingConfigData = [
            'name_asc' => [
                'translation' => 'name.asc',
                'query' => 'name.de asc'
            ],
            'price_asc' => [
                'translation' => 'price.asc',
                'query' => 'price asc'
            ],
            'name_desc' => [
                'translation' => 'name.desc',
                'query' => 'name.de desc'
            ],
        ];

        $configData = [
            'pagination' => [
                'products_per_page' => 23,
                'neighbours' => 4,
            ],
            'sorting' => [
                'choices' => $sortingConfigData,
                'default' => 'price_asc'
            ],
            'view' => [
                'default' => 'grid',
            ],
            'facet' => [
                'reset' => 'reset',
                'submit' => 'submit'
            ],
            'translation_domain' => 'messages',
            'search' => [
                'match_variants' => true,
                'enable_fuzzy' => true,
                'fuzzy_level' => 4
            ],
            'base_category_query' => 'custom(fields(alias="_SHOP_ROOT"))'
        ];

        $resolvedConfig = (new SearchConfigFactory($configData))->create();

        static::assertInstanceOf(SearchConfig::class, $resolvedConfig);
        static::assertEquals('price_asc', $resolvedConfig->getDefaultSorting());
        static::assertEquals('price_asc', $resolvedConfig->getDefaultSortingSearch());
        static::assertEquals('price_asc', $resolvedConfig->getDefaultSortingListing());
        static::assertEquals('grid', $resolvedConfig->getDefaultView());
        static::assertEquals(4, $resolvedConfig->getNeighbours());
        static::assertEquals(23, $resolvedConfig->getItemsPerPage());
        static::assertEquals($sortingConfigData, $resolvedConfig->getSortings());
        static::assertEquals('reset', $resolvedConfig->getFacet()['reset']);
        static::assertEquals('messages', $resolvedConfig->getTranslationDomain());
        static::assertEquals(true, $resolvedConfig->isMatchVariants());
        static::assertEquals(true, $resolvedConfig->getFuzzyConfig()->isActive());
        static::assertEquals(4, $resolvedConfig->getFuzzyConfig()->getLevel());
        static::assertEquals('custom(fields(alias="_SHOP_ROOT"))', $resolvedConfig->getBaseCategoryQuery());

        $configData['sorting']['default_listing'] = 'name_desc';
        $configData['sorting']['default_search'] = 'name_desc';

        $resolvedConfig = (new SearchConfigFactory($configData))->create();
        
        static::assertEquals('name_desc', $resolvedConfig->getDefaultSortingListing());
        static::assertEquals('name_desc', $resolvedConfig->getDefaultSortingSearch());
    }
}
