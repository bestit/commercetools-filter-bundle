<?php

namespace BestIt\Commercetools\FilterBundle\Manager;

use BestIt\Commercetools\FilterBundle\Builder\RequestBuilder;
use BestIt\Commercetools\FilterBundle\Builder\ResponseBuilder;
use BestIt\Commercetools\FilterBundle\Factory\SearchContextFactory;
use BestIt\Commercetools\FilterBundle\Factory\SortingFactory;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchResult;
use Commercetools\Core\Model\Category\Category;
use Symfony\Component\HttpFoundation\Request;

/**
 * Manager for filter products
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Manager
 */
class FilterManager implements FilterManagerInterface
{
    /**
     * Factory for creating a context object
     *
     * @var SearchContextFactory
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
     * @param SearchContextFactory $contextFactory
     * @param SortingFactory $sortingFactory
     * @param RequestBuilder $requestBuilder
     * @param ResponseBuilder $responseBuilder
     */
    public function __construct(
        SearchContextFactory $contextFactory,
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
     * @return SearchContextFactory
     */
    private function getContextFactory(): SearchContextFactory
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
     * {@inheritdoc}
     */
    public function listing(Request $request, Category $category, string $language = 'de'): SearchResult
    {
        $context = $this->getContextFactory()->createFromCategory($request, $category, $language);
        $sorting = $this->getSortingFactory()->create($context);

        $rawResponse = $this->getRequestBuilder()->execute($context, $sorting);
        $response = $this->getResponseBuilder()->build($context, $rawResponse);
        $response->setSorting($sorting);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function search(Request $request, string $search = null, string $language = 'de'): SearchResult
    {
        $context = $this->getContextFactory()->createFromSearch($request, $language, $search);
        $sorting = $this->getSortingFactory()->create($context);

        $rawResponse = $this->getRequestBuilder()->execute($context, $sorting);
        $response = $this->getResponseBuilder()->build($context, $rawResponse);
        $response->setSorting($sorting);

        return $response;
    }

    /**
     * Set contextFactory
     *
     * @param SearchContextFactory $contextFactory
     *
     * @return FilterManager
     */
    private function setContextFactory(SearchContextFactory $contextFactory): FilterManager
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
