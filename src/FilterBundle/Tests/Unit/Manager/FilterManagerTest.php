<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Manager;

use BestIt\Commercetools\FilterBundle\Builder\RequestBuilder;
use BestIt\Commercetools\FilterBundle\Builder\ResponseBuilder;
use BestIt\Commercetools\FilterBundle\Factory\ContextFactory;
use BestIt\Commercetools\FilterBundle\Factory\SortingFactory;
use BestIt\Commercetools\FilterBundle\Manager\FilterManager;
use BestIt\Commercetools\FilterBundle\Model\Config;
use BestIt\Commercetools\FilterBundle\Model\Context;
use BestIt\Commercetools\FilterBundle\Model\Result;
use BestIt\Commercetools\FilterBundle\Model\SortingCollection;
use Commercetools\Core\Model\Category\Category;
use Commercetools\Core\Response\PagedSearchResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FilterManagerTest
 * @author chowanski <chowanski@bestit-online.de>
 * @category Tests\Unit
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Manager
 * @version $id$
 */
class FilterManagerTest extends TestCase
{
    /**
     * The manager to test
     * @var FilterManager
     */
    private $fixture;

    /**
     * Factory for creating a context object
     * @var ContextFactory
     */
    private $contextFactory;

    /**
     * Factory for sorting
     * @var SortingFactory
     */
    private $sortingFactory;

    /**
     * Request builder
     * @var RequestBuilder
     */
    private $requestBuilder;

    /**
     * Response builder
     * @var ResponseBuilder
     */
    private $responseBuilder;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->fixture = new FilterManager(
            $this->contextFactory = static::createMock(ContextFactory::class),
            $this->sortingFactory = static::createMock(SortingFactory::class),
            $this->requestBuilder = static::createMock(RequestBuilder::class),
            $this->responseBuilder = static::createMock(ResponseBuilder::class)
        );
    }

    /**
     * Test listing
     * @return void
     */
    public function testListing()
    {
        $request = new Request();
        $category = new Category();
        $category->setId('e068f1d3-5ca4-4f15-a776-b2ba5b3efcb1');

        // Context
        $this->contextFactory
            ->expects(self::once())
            ->method('createFromCategory')
            ->with(self::equalTo($request))
            ->willReturn($context = new Context([
                'page' => 1,
                'config' => new Config([
                    'itemsPerPage' => 20
                ])
            ]));

        $this->sortingFactory
            ->expects(self::once())
            ->method('create')
            ->with(self::equalTo($context))
            ->willReturn($sortingCollection = new SortingCollection());

        $this->requestBuilder
            ->expects(self::once())
            ->method('execute')
            ->with(self::equalTo($context), self::equalTo($sortingCollection))
            ->willReturn($rawResponse = $this->createMock(PagedSearchResponse::class));

        $this->responseBuilder
            ->expects(self::once())
            ->method('build')
            ->with(self::equalTo($context), self::equalTo($rawResponse))
            ->willReturn($response = new Result());

        $result = $this->fixture->listing($request, $category);
        static::assertInstanceOf(Result::class, $result);
        static::assertEquals($sortingCollection, $result->getSorting());
    }

    /**
     * Test search method
     * @return void
     */
    public function testSearch()
    {
        $request = new Request();
        $search = 'foobar';

        // Context
        $this->contextFactory
            ->expects(self::once())
            ->method('createFromSearch')
            ->with(self::equalTo($request), self::equalTo($search))
            ->willReturn($context = new Context([
                'page' => 1,
                'config' => new Config([
                    'itemsPerPage' => 20
                ])
            ]));

        $this->sortingFactory
            ->expects(self::once())
            ->method('create')
            ->with(self::equalTo($context))
            ->willReturn($sortingCollection = new SortingCollection());

        $this->requestBuilder
            ->expects(self::once())
            ->method('execute')
            ->with(self::equalTo($context), self::equalTo($sortingCollection))
            ->willReturn($rawResponse = $this->createMock(PagedSearchResponse::class));

        $this->responseBuilder
            ->expects(self::once())
            ->method('build')
            ->with(self::equalTo($context), self::equalTo($rawResponse))
            ->willReturn($response = new Result());

        $result = $this->fixture->search($request, $search);
        static::assertInstanceOf(Result::class, $result);
        static::assertEquals($sortingCollection, $result->getSorting());
    }
}
