<?php

namespace BestIt\Commercetools\FilterBundle\Manager;

use BestIt\Commercetools\FilterBundle\Builder\RequestBuilder;
use BestIt\Commercetools\FilterBundle\Builder\ResponseBuilder;
use BestIt\Commercetools\FilterBundle\Factory\ContextFactory;
use BestIt\Commercetools\FilterBundle\Factory\SortingFactory;
use BestIt\Commercetools\FilterBundle\Model\Result;
use Commercetools\Core\Model\Category\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Exception\NotImplementedException;

/**
 * Manager for filter products
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Manager
 */
class FilterManager
{
    /**
     * Factory for creating a context object
     *
     * @var ContextFactory
     */
    private $contextFactory;

    /**
     * Factory for sorting
     *
     * @var SortingFactory
     */
    private $sortingFactory;

    /**
     * Request builder
     *
     * @var RequestBuilder
     */
    private $requestBuilder;

    /**
     * Response builder
     *
     * @var ResponseBuilder
     */
    private $responseBuilder;

    /**
     * FilterManager constructor.
     *
     * @param ContextFactory $contextFactory
     * @param SortingFactory $sortingFactory
     * @param RequestBuilder $requestBuilder
     * @param ResponseBuilder $responseBuilder
     */
    public function __construct(
        ContextFactory $contextFactory,
        SortingFactory $sortingFactory,
        RequestBuilder $requestBuilder,
        ResponseBuilder $responseBuilder
    ) {
        $this
            ->setContextFactory($contextFactory)
            ->setSortingFactory($sortingFactory)
            ->setRequestBuilder($requestBuilder)
            ->setResponseBuilder($responseBuilder);
    }

    /**
     * Get contextFactory
     *
     * @return ContextFactory
     */
    private function getContextFactory(): ContextFactory
    {
        return $this->contextFactory;
    }

    /**
     * Get requestBuilder
     *
     * @return RequestBuilder
     */
    private function getRequestBuilder(): RequestBuilder
    {
        return $this->requestBuilder;
    }

    /**
     * Get responseBuilder
     *
     * @return ResponseBuilder
     */
    private function getResponseBuilder(): ResponseBuilder
    {
        return $this->responseBuilder;
    }

    /**
     * Get sortingFactory
     *
     * @return SortingFactory
     */
    private function getSortingFactory(): SortingFactory
    {
        return $this->sortingFactory;
    }

    /**
     * Perform a listing request
     *
     * @param Request $request
     * @param Category $category
     *
     * @return Result
     */
    public function listing(Request $request, Category $category): Result
    {
        $context = $this->getContextFactory()->createFromCategory($request, $category);
        $sorting = $this->getSortingFactory()->create($context);

        $rawResponse = $this->getRequestBuilder()->execute($context, $sorting);
        $response = $this->getResponseBuilder()->build($context, $rawResponse);
        $response->setSorting($sorting);

        return $response;
    }

    /**
     * Perform a search request
     *
     * @param Request $request
     * @param string $search
     *
     * @return Result
     */
    public function search(Request $request, string $search = null): Result
    {
        $context = $this->getContextFactory()->createFromSearch($request, $search);
        $sorting = $this->getSortingFactory()->create($context);

        $rawResponse = $this->getRequestBuilder()->execute($context, $sorting);
        $response = $this->getResponseBuilder()->build($context, $rawResponse);
        $response->setSorting($sorting);

        return $response;
    }

    /**
     * Set contextFactory
     *
     * @param ContextFactory $contextFactory
     *
     * @return FilterManager
     */
    private function setContextFactory(ContextFactory $contextFactory): FilterManager
    {
        $this->contextFactory = $contextFactory;

        return $this;
    }

    /**
     * Set requestBuilder
     *
     * @param RequestBuilder $requestBuilder
     *
     * @return FilterManager
     */
    private function setRequestBuilder(RequestBuilder $requestBuilder): FilterManager
    {
        $this->requestBuilder = $requestBuilder;

        return $this;
    }

    /**
     * Set responseBuilder
     *
     * @param ResponseBuilder $responseBuilder
     *
     * @return FilterManager
     */
    private function setResponseBuilder(ResponseBuilder $responseBuilder): FilterManager
    {
        $this->responseBuilder = $responseBuilder;

        return $this;
    }

    /**
     * Set sortingFactory
     *
     * @param SortingFactory $sortingFactory
     *
     * @return FilterManager
     */
    private function setSortingFactory(SortingFactory $sortingFactory): FilterManager
    {
        $this->sortingFactory = $sortingFactory;

        return $this;
    }
}
