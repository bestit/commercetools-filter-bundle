<?php

namespace BestIt\Commercetools\FilterBundle\Builder;

use BestIt\Commercetools\FilterBundle\Helper\FacetConfigCollectionAwareTrait;
use BestIt\Commercetools\FilterBundle\Model\Context;
use BestIt\Commercetools\FilterBundle\Model\FacetConfigCollection;
use BestIt\Commercetools\FilterBundle\Model\SortingCollection;
use Commercetools\Core\Client;
use Commercetools\Core\Model\Product\Search\Filter;
use Commercetools\Core\Request\Products\ProductProjectionSearchRequest;
use Commercetools\Core\Response\PagedSearchResponse;

/**
 * Builder and executer of requests
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Builder
 */
class RequestBuilder
{
    use FacetConfigCollectionAwareTrait;

    /**
     * The client for executing
     *
     * @var Client
     */
    private $client;

    /**
     * RequestManager constructor.
     *
     * @param Client $client
     * @param FacetConfigCollection $facetConfigCollection
     */
    public function __construct(Client $client, FacetConfigCollection $facetConfigCollection)
    {
        $this
            ->setClient($client)
            ->setFacetConfigCollection($facetConfigCollection);
    }

    /**
     * Get client
     *
     * @return Client
     */
    private function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Set client
     *
     * @param Client $client
     *
     * @return RequestBuilder
     */
    private function setClient(Client $client): RequestBuilder
    {
        $this->client = $client;
        return $this;
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
     * @param Context $context
     * @param SortingCollection $sorting
     *
     * @return PagedSearchResponse
     */
    public function execute(Context $context, SortingCollection $sorting): PagedSearchResponse
    {
        $request = ProductProjectionSearchRequest::of()
            ->offset(($context->getPage() - 1) * $context->getConfig()->getItemsPerPage())
            ->limit($context->getConfig()->getItemsPerPage())
            ->sort($sorting->getActive()->getQuery())
            ->expand('masterVariant.attributes[*].value')
            ->expand('productType');

        // Filter to category if exists
        if ($category = $context->getCategory()) {
            $request->addFilter($filter = new Filter('categories.id', $category->getId()));
            $request->addFilterQuery($filter);
        }

        // Filter to search value if exists
        if ($search = $context->getSearch()) {
            $request->addParam('text.de', $search);
        }

        $builder = new FacetBuilder($this->getFacetConfigCollection());
        $resolvedValues = $builder->resolve($this->decode($context->getQuery()));
        $request = $builder->build($request, $resolvedValues);

        /**
         * Commercetools response
         *
         * @var PagedSearchResponse $result
         */
        $result = $this->getClient()->execute($request);

        return $result;
    }
}
