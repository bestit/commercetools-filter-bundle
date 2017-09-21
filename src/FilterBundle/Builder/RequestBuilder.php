<?php

namespace BestIt\Commercetools\FilterBundle\Builder;

use BestIt\Commercetools\FilterBundle\Event\Request\ProductProjectionSearchRequestEvent;
use BestIt\Commercetools\FilterBundle\FilterEvent;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchContext;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfigCollection;
use BestIt\Commercetools\FilterBundle\Model\Sorting\SortingCollection;
use Commercetools\Core\Client;
use Commercetools\Core\Model\Product\Search\Filter;
use Commercetools\Core\Request\Products\ProductProjectionSearchRequest;
use Commercetools\Core\Response\PagedSearchResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Builder and executer of requests
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Builder
 */
class RequestBuilder
{
    /**
     * The client for executing
     *
     * @var Client
     */
    private $client;

    /**
     * The event dispatcher
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * The collection
     *
     * @var FacetConfigCollection or null
     */
    private $facetConfigCollection;

    /**
     * RequestManager constructor.
     *
     * @param Client $client
     * @param FacetConfigCollection $facetConfigCollection
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        Client $client,
        FacetConfigCollection $facetConfigCollection,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->client = $client;
        $this->eventDispatcher = $eventDispatcher;
        $this->facetConfigCollection = $facetConfigCollection;
    }

    /**
     * Decode facet query
     *
     * @param array $query
     *
     * @return array
     * @TODO:  Add query encoder / decoder service
     */
    public function decode(array $query): array
    {
        return $query['filter'] ?? [];
    }

    /**
     * Execute request
     *
     * @param SearchContext $context
     * @param SortingCollection $sorting
     *
     * @return PagedSearchResponse
     */
    public function execute(SearchContext $context, SortingCollection $sorting): PagedSearchResponse
    {
        $request = ProductProjectionSearchRequest::of()
            ->offset(($context->getPage() - 1) * $context->getConfig()->getItemsPerPage())
            ->limit($context->getConfig()->getItemsPerPage())
            ->sort($sorting->getActive()->getQuery())
            ->markMatchingVariants($context->getConfig()->isMatchVariants());

        // Filter to category if exists
        if ($category = $context->getCategory()) {
            $request->addFilter($filter = new Filter('categories.id', $category->getId()));
            $request->addFilterQuery($filter);
        }

        // Filter to search value if exists
        if ($search = $context->getSearch()) {
            foreach ($this->client->getConfig()->getContext()->getLanguages() as $language) {
                $request->addParam(sprintf('text.%s', $language), $search);
            }

            // Fuzzy
            $fuzzyConfig = $context->getConfig()->getFuzzyConfig();
            $request->fuzzy($fuzzyConfig->isActive());
            if ($fuzzyConfig->isActive() && $fuzzyConfig->getLevel() !== null) {
                $request->fuzzy($fuzzyConfig->getLevel());
            }
        }

        $builder = new FacetBuilder($this->facetConfigCollection);
        $resolvedValues = $builder->resolve($this->decode($context->getQuery()));
        $request = $builder->build($request, $resolvedValues);

        $event = new ProductProjectionSearchRequestEvent($request);
        $this->eventDispatcher->dispatch(FilterEvent::PRODUCTS_REQUEST_POST, $event);

        /**
         * Commercetools response
         *
         * @var PagedSearchResponse $result
         */
        $result = $this->client->execute($event->getRequest());

        return $result;
    }
}
