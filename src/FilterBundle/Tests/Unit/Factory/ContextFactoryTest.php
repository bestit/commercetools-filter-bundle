<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Factory;

use BestIt\Commercetools\FilterBundle\Factory\SearchContextFactory;
use BestIt\Commercetools\FilterBundle\Form\FilterType;
use BestIt\Commercetools\FilterBundle\Generator\FilterUrlGeneratorInterface;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchConfig;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchContext;
use BestIt\Commercetools\FilterBundle\Repository\CategoryRepository;
use Commercetools\Core\Model\Category\Category;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ContextFactoryTest
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 * @version    $id$
 */
class ContextFactoryTest extends TestCase
{
    /**
     * The context factory
     *
     * @var SearchContextFactory
     */
    private $fixture;

    /**
     * The config array
     *
     * @var SearchConfig
     */
    private $config;

    /**
     * The filter url generator
     *
     * @var FilterUrlGeneratorInterface
     */
    private $filterUrlGenerator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->config = new SearchConfig(
            [
                'defaultView' => 'grid',
                'defaultSorting' => 'name_asc',
                'neighbours' => 3,
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
            ]
        );

        $this->fixture = new SearchContextFactory(
            $this->config,
            $this->filterUrlGenerator = $this->createMock(FilterUrlGeneratorInterface::class),
            $this->createMock(CategoryRepository::class)
        );
    }

    /**
     * Test create from category with standard fields
     *
     * @return void
     */
    public function testCreateFromCategory()
    {
        $request = new Request();
        $category = new Category();

        $this->filterUrlGenerator
            ->expects(static::once())
            ->method('generateByCategory')
            ->with($request, $category)
            ->willReturn('foo-route');

        $context = new SearchContext(
            [
                'page' => 1,
                'view' => 'grid',
                'query' => [],
                'config' => $this->config,
                'route' => $category,
                'baseUrl' => 'foo-route',
                'sorting' => 'name_asc',
                'category' => $category,
                'language' => 'en'
            ]
        );

        static::assertEquals($context, $this->fixture->createFromCategory($request, $category, 'en'));
    }

    /**
     * Test create from category with query fields
     *
     * @return void
     */
    public function testCreateFromCategoryWithQuery()
    {
        $request = new Request([
            'filter' => [
                FilterType::FIELDNAME_PAGE => 4,
                FilterType::FIELDNAME_VIEW => 'list'
            ]
        ]);
        $category = new Category();

        $this->filterUrlGenerator
            ->expects(static::once())
            ->method('generateByCategory')
            ->with($request, $category)
            ->willReturn('foo-route');

        $context = new SearchContext(
            [
                'page' => 4,
                'view' => 'list',
                'query' => [
                    'filter' => [
                        FilterType::FIELDNAME_PAGE => 4,
                        FilterType::FIELDNAME_VIEW => 'list'
                    ]
                ],
                'config' => $this->config,
                'route' => $category,
                'baseUrl' => 'foo-route',
                'sorting' => 'name_asc',
                'category' => $category,
                'language' => 'es'
            ]
        );

        static::assertEquals($context, $this->fixture->createFromCategory($request, $category, 'es'));
    }

    /**
     * Test create from search with standard fields
     *
     * @return void
     */
    public function testCreateFromSearch()
    {
        $request = new Request();
        $search = 'foobar';

        $this->filterUrlGenerator
            ->expects(static::once())
            ->method('generateBySearch')
            ->with($request, $search)
            ->willReturn('foo-route');

        $context = new SearchContext(
            [
                'page' => 1,
                'view' => 'grid',
                'query' => [],
                'config' => $this->config,
                'route' => 'search_index',
                'baseUrl' => 'foo-route',
                'sorting' => 'name_asc',
                'search' => $search,
                'language' => 'de'
            ]
        );

        static::assertEquals($context, $this->fixture->createFromSearch($request, 'de', $search));
    }

    /**
     * Test create from search with query fields
     *
     * @return void
     */
    public function testCreateFromSearchWithQuery()
    {
        $request = new Request([
            'filter' => [
                FilterType::FIELDNAME_PAGE => 4,
                FilterType::FIELDNAME_VIEW => 'list'
            ]
        ]);
        $search = 'foobar';

        $this->filterUrlGenerator
            ->expects(static::once())
            ->method('generateBySearch')
            ->with($request, $search)
            ->willReturn('foo-route');

        $context = new SearchContext(
            [
                'page' => 4,
                'view' => 'list',
                'query' => [
                    'filter' => [
                        FilterType::FIELDNAME_PAGE => 4,
                        FilterType::FIELDNAME_VIEW => 'list'
                    ]
                ],
                'config' => $this->config,
                'route' => 'search_index',
                'baseUrl' => 'foo-route',
                'sorting' => 'name_asc',
                'search' => $search,
                'language' => 'es'
            ]
        );

        static::assertEquals($context, $this->fixture->createFromSearch($request, 'es', $search));
    }

    /**
     * Test create from search with query fields
     *
     * @return void
     */
    public function testCreateFromSearchWithoutCategory()
    {
        $request = new Request([
            'filter' => [
                FilterType::FIELDNAME_PAGE => 4,
                FilterType::FIELDNAME_VIEW => 'list'
            ]
        ]);
        $search = null;

        $this->filterUrlGenerator
            ->expects(static::once())
            ->method('generateBySearch')
            ->with($request, $search)
            ->willReturn('foo-route');

        $context = new SearchContext(
            [
                'page' => 4,
                'view' => 'list',
                'query' => [
                    'filter' => [
                        FilterType::FIELDNAME_PAGE => 4,
                        FilterType::FIELDNAME_VIEW => 'list'
                    ]
                ],
                'config' => $this->config,
                'route' => 'search_index',
                'baseUrl' => 'foo-route',
                'sorting' => 'name_asc',
                'search' => null,
                'language' => 'fr'
            ]
        );

        static::assertEquals($context, $this->fixture->createFromSearch($request, 'fr', $search));
    }
}
