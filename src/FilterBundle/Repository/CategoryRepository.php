<?php

namespace BestIt\Commercetools\FilterBundle\Repository;

use BestIt\Commercetools\FilterBundle\Exception\ApiException;
use Commercetools\Core\Client;
use Commercetools\Core\Model\Category\Category;
use Commercetools\Core\Model\Category\CategoryCollection;
use Commercetools\Core\Request\Categories\CategoryQueryRequest;
use Commercetools\Core\Response\ErrorResponse;

/**
 * Class CategoryRepository
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle\Repository
 */
class CategoryRepository
{
    /**
     * The commerce tools client
     *
     * @var Client
     */
    private $client;

    /**
     * CategoryRepository constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Find one category or return null
     *
     * @param string $query
     *
     * @return Category|null
     *
     * @throws ApiException
     */
    public function findOneBy(string $query)
    {
        $request = CategoryQueryRequest::of();
        $request->where($query);
        $request->limit(1);

        $response = $this->client->execute($request);
        if ($response instanceof ErrorResponse) {
            throw new ApiException(sprintf('Could not fetch category by query: "%s"', $query));
        }

        /** @var CategoryCollection $collection */
        $collection = $response->toObject();

        if ($collection->count() > 0) {
            return $collection->getAt(0);
        }

        return null;
    }
}
