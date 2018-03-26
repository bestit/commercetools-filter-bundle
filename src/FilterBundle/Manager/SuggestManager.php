<?php

namespace BestIt\Commercetools\FilterBundle\Manager;

use BestIt\Commercetools\FilterBundle\Event\Request\ProductProjectionSearchRequestEvent;
use BestIt\Commercetools\FilterBundle\Event\Request\ProductsSuggestRequestEvent;
use BestIt\Commercetools\FilterBundle\Exception\ApiException;
use BestIt\Commercetools\FilterBundle\Model\Suggest\KeywordsResult;
use BestIt\Commercetools\FilterBundle\Model\Suggest\SuggestConfig;
use BestIt\Commercetools\FilterBundle\Model\Suggest\SuggestResult;
use BestIt\Commercetools\FilterBundle\Normalizer\ProductNormalizerInterface;
use BestIt\Commercetools\FilterBundle\Repository\CategoryRepository;
use BestIt\Commercetools\FilterBundle\SuggestEvent;
use Commercetools\Core\Client;
use Commercetools\Core\Model\Product\Search\Filter;
use Commercetools\Core\Model\Product\SuggestionResult;
use Commercetools\Core\Request\Products\ProductProjectionSearchRequest;
use Commercetools\Core\Request\Products\ProductsSuggestRequest;
use Commercetools\Core\Response\ErrorResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Manager for suggest requests
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Manager
 */
class SuggestManager implements SuggestManagerInterface
{
    /**
     * The commercetools client
     *
     * @var Client
     */
    private $client;

    /**
     * The product normalizer
     *
     * @var ProductNormalizerInterface
     */
    private $productNormalizer;

    /**
     * The event dispatcher
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * The suggest config
     *
     * @var SuggestConfig
     */
    private $config;

    /**
     * The category repository
     *
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * SuggestManager constructor.
     *
     * @param Client $client
     * @param ProductNormalizerInterface $productNormalizer
     * @param EventDispatcherInterface $eventDispatcher
     * @param SuggestConfig $config
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        Client $client,
        ProductNormalizerInterface $productNormalizer,
        EventDispatcherInterface $eventDispatcher,
        SuggestConfig $config,
        CategoryRepository $categoryRepository
    ) {
        $this->client = $client;
        $this->productNormalizer = $productNormalizer;
        $this->eventDispatcher = $eventDispatcher;
        $this->config = $config;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getKeywords(string $keyword, int $max, string $language = 'de'): KeywordsResult
    {
        $collection = [];
        $request = new ProductsSuggestRequest();
        $request->addKeyword($language, $keyword);

        $request->limit($max);
        $request->fuzzy(false);

        // Fuzzy
        $fuzzyConfig = $this->config->getFuzzyConfig();
        $request->fuzzy($fuzzyConfig->isActive());
        if ($fuzzyConfig->isActive() && $fuzzyConfig->getLevel() !== null) {
            $request->fuzzy($fuzzyConfig->getLevel());
        }

        $event = new ProductsSuggestRequestEvent($request);
        $this->eventDispatcher->dispatch(SuggestEvent::KEYWORDS_REQUEST_POST, $event);

        $response = $this->client->execute($event->getRequest());

        if ($response->isError() && $response instanceof ErrorResponse) {
            throw new ApiException($response->getMessage(), $response->getStatusCode());
        }

        /**
         * The suggest result
         *
         * @var SuggestionResult $suggests
         */
        $suggests = $response->toObject();
        foreach ($suggests->getSearchKeywords() as $keywordCollection) {
            foreach ($keywordCollection as $keywordItem) {
                $collection[$keywordItem->getText()] = $keywordItem->getText();
            }
        }

        $result = new KeywordsResult();
        $result->setKeywords($collection);
        $result->setHttpResponse($response);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getProducts(string $keyword, int $max, string $language = 'de'): SuggestResult
    {
        $request = ProductProjectionSearchRequest::of();
        $request->addParam(sprintf('text.%s', $language), $keyword);

        $request->limit($max);
        $request->fuzzy(false);

        // Fuzzy
        $fuzzyConfig = $this->config->getFuzzyConfig();
        $request->fuzzy($fuzzyConfig->isActive());
        if ($fuzzyConfig->isActive() && $fuzzyConfig->getLevel() !== null) {
            $request->fuzzy($fuzzyConfig->getLevel());
        }

        // Match variants
        $request->markMatchingVariants($this->config->isMatchVariants());

        // Corral request
        $category = null;
        if ($query = $this->config->getBaseCategoryQuery()) {
            if ($category = $this->categoryRepository->findOneBy($query)) {
                $request->addFilterQuery(new Filter('categories.id', $category->getId()));
            } else {
                throw new ApiException(sprintf('Could not fetch any base category by query: "%s"', $query));
            }
        }

        $event = new ProductProjectionSearchRequestEvent($request, $keyword);
        $this->eventDispatcher->dispatch(SuggestEvent::PRODUCTS_REQUEST_POST, $event);

        $response = $this->client->execute($event->getRequest());

        if ($response->isError() && $response instanceof ErrorResponse) {
            throw new ApiException($response->getMessage(), $response->getStatusCode());
        }

        $products = [];
        foreach ($response->toObject() as $product) {
            $products[] = $this->productNormalizer->normalize($product);
        }

        $result = new SuggestResult();
        $result
            ->setProducts($products)
            ->setHttpResponse($response);

        if ($category !== null) {
            $result->setBaseCategory($category);
        }

        return $result;
    }
}
