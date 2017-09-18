<?php

namespace BestIt\Commercetools\FilterBundle\Manager;

use BestIt\Commercetools\FilterBundle\Event\Request\ProductProjectionSearchRequestEvent;
use BestIt\Commercetools\FilterBundle\Event\Request\ProductsSuggestRequestEvent;
use BestIt\Commercetools\FilterBundle\Exception\ApiException;
use BestIt\Commercetools\FilterBundle\Normalizer\ProductNormalizerInterface;
use BestIt\Commercetools\FilterBundle\SuggestEvent;
use Commercetools\Core\Client;
use Commercetools\Core\Model\Common\LocalizedString;
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
     * Mark matching variant?
     *
     * @var bool $markMatchingVariant
     */
    private $markMatchingVariant;

    /**
     * SuggestManager constructor.
     *
     * @param Client $client
     * @param ProductNormalizerInterface $productNormalizer
     * @param EventDispatcherInterface $eventDispatcher
     * @param bool $markMatchingVariant Mark matching variants?
     */
    public function __construct(
        Client $client,
        ProductNormalizerInterface $productNormalizer,
        EventDispatcherInterface $eventDispatcher,
        bool $markMatchingVariant
    ) {
        $this->client = $client;
        $this->productNormalizer = $productNormalizer;
        $this->eventDispatcher = $eventDispatcher;
        $this->markMatchingVariant = $markMatchingVariant;
    }

    /**
     * {@inheritdoc}
     */
    public function getKeywords(string $keyword, int $max): array
    {
        $collection = [];
        $request = ProductsSuggestRequest::ofKeywords(LocalizedString::ofLangAndText('de', $keyword));
        $request->fuzzy(true);
        $request->limit($max);

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
            foreach ($keywordCollection as $keyword) {
                $collection[$keyword->getText()] = $keyword->getText();
            }
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function getProducts(string $keyword, int $max): array
    {
        $request = ProductProjectionSearchRequest::of();
        $request->addParam('text.de', $keyword);
        $request->fuzzy(true);
        $request->limit($max);
        $request->markMatchingVariants($this->markMatchingVariant);

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

        return $products;
    }
}
