<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Builder;

use BestIt\Commercetools\FilterBundle\Builder\FacetBuilder;
use BestIt\Commercetools\FilterBundle\Builder\RequestBuilder;
use BestIt\Commercetools\FilterBundle\Enum\FacetType;
use BestIt\Commercetools\FilterBundle\Event\Request\ProductProjectionSearchRequestEvent;
use BestIt\Commercetools\FilterBundle\FilterEvent;
use BestIt\Commercetools\FilterBundle\Model\Config;
use BestIt\Commercetools\FilterBundle\Model\Context;
use BestIt\Commercetools\FilterBundle\Model\FacetConfig;
use BestIt\Commercetools\FilterBundle\Model\FacetConfigCollection;
use BestIt\Commercetools\FilterBundle\Model\Sorting;
use BestIt\Commercetools\FilterBundle\Model\SortingCollection;
use Commercetools\Core\Client;
use Commercetools\Core\Request\Products\ProductProjectionSearchRequest;
use Commercetools\Core\Response\PagedSearchResponse;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Test for request builder
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Builder
 * @version    $id$
 */
class RequestBuilderTest extends TestCase
{
    /**
     * The builder to test
     *
     * @var RequestBuilder
     */
    private $fixture;

    /**
     * The client
     *
     * @var Client|PHPUnit_Framework_MockObject_MockObject
     */
    private $client;

    /**
     * The facet config collection
     *
     * @var FacetConfigCollection
     */
    private $facetConfigCollection;

    /**
     * The event dispatcher
     *
     * @var EventDispatcherInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $eventDispatcher;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new RequestBuilder(
            $this->client = $this->createMock(Client::class),
            $this->facetConfigCollection = new FacetConfigCollection(),
            $this->eventDispatcher = static::createMock(EventDispatcherInterface::class),
            true
        );
    }

    /**
     * Test build function without special params
     *
     * @return void
     */
    public function testDefaultBuild()
    {
        $context = new Context(
            [
                'config' => $config = new Config(
                    [
                        'itemsPerPage' => 20
                    ]
                ),
                'page' => 1,
                'query' => ['foo' => 'bar']
            ]
        );

        $sortingCollection = new SortingCollection(
            [
                new Sorting('name_desc', 'name_desc', 'name desc'),
                $active = new Sorting('name_asc', 'name_asc', 'name asc'),
                $default = new Sorting('price_asc', 'price_asc', 'price asc'),
            ]
        );

        $sortingCollection->setActive($active);
        $sortingCollection->setDefault($default);

        $request = ProductProjectionSearchRequest::of()
            ->offset(($context->getPage() - 1) * $context->getConfig()->getItemsPerPage())
            ->limit($context->getConfig()->getItemsPerPage())
            ->sort('name asc')
            ->markMatchingVariants(true);

        $this->eventDispatcher
            ->expects(static::once())
            ->method('dispatch')
            ->with(
                static::equalTo(FilterEvent::PRODUCTS_REQUEST_POST),
                static::isInstanceOf(ProductProjectionSearchRequestEvent::class)
            );

        $this->client
            ->expects(self::once())
            ->method('execute')
            ->with(self::equalTo($request))
            ->willReturn($response = $this->createMock(PagedSearchResponse::class));

        static::assertEquals($response, $this->fixture->execute($context, $sortingCollection));
    }

    /**
     * Test build function with facets
     *
     * @return void
     */
    public function testFacetBuild()
    {
        $context = new Context(
            [
                'config' => $config = new Config(
                    [
                        'itemsPerPage' => 20
                    ]
                ),
                'page' => 1,
                'query' => [
                    'foo' => 'bar',
                    'filter' => [
                        'foobar' => 'bestit'
                    ]
                ],
            ]
        );

        $sortingCollection = new SortingCollection(
            [
                new Sorting('name_desc', 'name_desc', 'name desc'),
                $active = new Sorting('name_asc', 'name_asc', 'name asc'),
                $default = new Sorting('price_asc', 'price_asc', 'price asc'),
            ]
        );

        $sortingCollection->setActive($active);
        $sortingCollection->setDefault($default);

        $this->facetConfigCollection->add(
            $facetConfig = (new FacetConfig())
                ->setName('foobar')
                ->setField('foobar')
                ->setAlias('foobar')
                ->setType(FacetType::TEXT)
        );

        $request = ProductProjectionSearchRequest::of()
            ->offset(($context->getPage() - 1) * $context->getConfig()->getItemsPerPage())
            ->limit($context->getConfig()->getItemsPerPage())
            ->sort('name asc')
            ->markMatchingVariants(true);

        $builder = new FacetBuilder($this->facetConfigCollection);
        $resolvedValues = $builder->resolve($context->getQuery()['filter']);
        $request = $builder->build($request, $resolvedValues);

        $this->eventDispatcher
            ->expects(static::once())
            ->method('dispatch')
            ->with(
                static::equalTo(FilterEvent::PRODUCTS_REQUEST_POST),
                static::isInstanceOf(ProductProjectionSearchRequestEvent::class)
            );

        $this->client
            ->expects(self::once())
            ->method('execute')
            ->with(self::equalTo($request))
            ->willReturn($response = $this->createMock(PagedSearchResponse::class));

        static::assertEquals($response, $this->fixture->execute($context, $sortingCollection));
    }
}
