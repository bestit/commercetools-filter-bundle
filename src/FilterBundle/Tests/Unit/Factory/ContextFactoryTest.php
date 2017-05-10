<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Factory;

use BestIt\Commercetools\FilterBundle\Factory\ContextFactory;
use BestIt\Commercetools\FilterBundle\Model\Config;
use BestIt\Commercetools\FilterBundle\Model\Context;
use Commercetools\Core\Model\Category\Category;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ContextFactoryTest
 * @author chowanski <chowanski@bestit-online.de>
 * @category Tests\Unit
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 * @version $id$
 */
class ContextFactoryTest extends TestCase
{
    /**
     * The context factory
     * @var ContextFactory
     */
    private $fixture;

    /**
     * The config array
     * @var Config
     */
    private $config;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->config = new Config([
            'defaultView' => 'grid',
            'defaultSorting' => 'name_asc',
            'neighbours' => 3,
            'pageQueryKey' => 'p',
            'sortQueryKey' => 's',
            'itemsPerPage' => 30,
            'viewQueryKey' => 'v',
            'sortings' => [
                'price_asc' => [
                    'translation' => 'sorting_price_asc',
                    'query' => 'price asc',
                    'default' => false
                ],
                'price_desc' => [
                    'translation' => 'sorting_price_desc',
                    'query' => 'price desc',
                    'default' => true
                ]
            ]
        ]);

        $this->fixture = new ContextFactory($this->config);
    }

    /**
     * Test create from category with standard fields
     * @return void
     */
    public function testCreateFromCategory()
    {
        $request = new Request();
        $category = new Category();

        $context = new Context([
            'page' => 1,
            'view' => 'grid',
            'query' => [],
            'config' => $this->config,
            'route' => $category,
            'sorting' => 'name_asc',
            'category' => $category
        ]);

        static::assertEquals($context, $this->fixture->createFromCategory($request, $category));
    }

    /**
     * Test create from category with query fields
     * @return void
     */
    public function testCreateFromCategoryWithQuery()
    {
        $request = new Request(['p' => 4, 'v' => 'list']);
        $category = new Category();

        $context = new Context([
            'page' => 4,
            'view' => 'list',
            'query' => ['p' => 4, 'v' => 'list'],
            'config' => $this->config,
            'route' => $category,
            'sorting' => 'name_asc',
            'category' => $category
        ]);

        static::assertEquals($context, $this->fixture->createFromCategory($request, $category));
    }

    /**
     * Test create from search with standard fields
     * @return void
     */
    public function testCreateFromSearch()
    {
        $request = new Request();
        $search = 'foobar';

        $context = new Context([
            'page' => 1,
            'view' => 'grid',
            'query' => [],
            'config' => $this->config,
            'route' => 'search_index',
            'sorting' => 'name_asc',
            'search' => $search
        ]);

        static::assertEquals($context, $this->fixture->createFromSearch($request, $search));
    }

    /**
     * Test create from search with query fields
     * @return void
     */
    public function testCreateFromSearchWithQuery()
    {
        $request = new Request(['p' => 4, 'v' => 'list']);
        $search = 'foobar';

        $context = new Context([
            'page' => 4,
            'view' => 'list',
            'query' => ['p' => 4, 'v' => 'list'],
            'config' => $this->config,
            'route' => 'search_index',
            'sorting' => 'name_asc',
            'search' => $search
        ]);

        static::assertEquals($context, $this->fixture->createFromSearch($request, $search));
    }

    /**
     * Test create from search with query fields
     * @return void
     */
    public function testCreateFromSearchWithoutCategory()
    {
        $request = new Request(['p' => 4, 'v' => 'list']);
        $search = null;

        $context = new Context([
            'page' => 4,
            'view' => 'list',
            'query' => ['p' => 4, 'v' => 'list'],
            'config' => $this->config,
            'route' => 'search_index',
            'sorting' => 'name_asc',
            'search' => null
        ]);

        static::assertEquals($context, $this->fixture->createFromSearch($request, $search));
    }
}
