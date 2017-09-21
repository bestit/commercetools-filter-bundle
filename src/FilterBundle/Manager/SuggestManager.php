<?php

namespace BestIt\Commercetools\FilterBundle\Manager;

use BestIt\Commercetools\FilterBundle\Event\Request\ProductProjectionSearchRequestEvent;
use BestIt\Commercetools\FilterBundle\Event\Request\ProductsSuggestRequestEvent;
use BestIt\Commercetools\FilterBundle\Exception\ApiException;
use BestIt\Commercetools\FilterBundle\Model\Suggest\KeywordsResult;
use BestIt\Commercetools\FilterBundle\Model\Suggest\SuggestConfig;
use BestIt\Commercetools\FilterBundle\Model\Suggest\SuggestResult;
use BestIt\Commercetools\FilterBundle\Normalizer\ProductNormalizerInterface;
use BestIt\Commercetools\FilterBundle\SuggestEvent;
use Commercetools\Core\Client;
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
     * SuggestManager constructor.
     *
     * @param Client $client
     * @param ProductNormalizerInterface $productNormalizer
     * @param EventDispatcherInterface $eventDispatcher
     * @param SuggestConfig $config
     */
    public function __construct(
        Client $client,
        ProductNormalizerInterface $productNormalizer,
        EventDispatcherInterface $eventDispatcher,
        SuggestConfig $config
    ) {
        $this->client = $client;
        $this->productNormalizer = $productNormalizer;
        $this->eventDispatcher = $eventDispatcher;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getKeywords(string $keyword, int $max): KeywordsResult
    {
        $collection = [];
        $request = new ProductsSuggestRequest();
        foreach ($this->client->getConfig()->getContext()->getLanguages() as $language) {
            $request->addKeyword($language, $keyword);
        }

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
    public function getProducts(string $keyword, int $max): SuggestResult
    {
        $request = ProductProjectionSearchRequest::of();
        foreach ($this->client->getConfig()->getContext()->getLanguages() as $language) {
            $request->addParam(sprintf('text.%s', $language), $keyword);
        }

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

        $event = new ProductProjectionSearchRequestEvent($request);
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
        $result->setProducts($products);
        $result->setHttpResponse($response);

        return $result;
    }
}
