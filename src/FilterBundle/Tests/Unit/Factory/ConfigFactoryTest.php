<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Factory;

use BestIt\Commercetools\FilterBundle\Factory\ConfigFactory;
use BestIt\Commercetools\FilterBundle\Model\Config;
use PHPUnit\Framework\TestCase;

/**
 * Test for config factory
 * @author chowanski <chowanski@bestit-online.de>
 * @category Tests\Unit
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 * @version $id$
 */
class ConfigFactoryTest extends TestCase
{
    /**
     * Test create config
     * @return void
     */
    public function testCreate()
    {
        $sortingConfigData = [
            'name_asc' => [
                'default' => false,
                'translation' => 'name.asc',
                'query' => 'name.de asc'
            ],
            'price_asc' => [
                'default' => true,
                'translation' => 'price.asc',
                'query' => 'price asc'
            ],
            'name_desc' => [
                'default' => false,
                'translation' => 'name.desc',
                'query' => 'name.de desc'
            ],
        ];

        $configData = [
            'products_per_page' => 23,
            'default_view' => 'grid',
            'neighbours' => 4,
            'page_query_key' => 'page',
            'sort_query_key' => 'sort',
            'view_query_key' => 'view',
            'sorting' => $sortingConfigData
        ];

        $resolvedConfig = (new ConfigFactory($configData))->create();

        static::assertInstanceOf(Config::class, $resolvedConfig);
        static::assertEquals('price_asc', $resolvedConfig->getDefaultSorting());
        static::assertEquals('sort', $resolvedConfig->getSortQueryKey());
        static::assertEquals('grid', $resolvedConfig->getDefaultView());
        static::assertEquals('view', $resolvedConfig->getViewQueryKey());
        static::assertEquals('page', $resolvedConfig->getPageQueryKey());
        static::assertEquals(4, $resolvedConfig->getNeighbours());
        static::assertEquals(23, $resolvedConfig->getItemsPerPage());
        static::assertEquals($sortingConfigData, $resolvedConfig->getSortings());
    }
}
