<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Manager;

use BestIt\Commercetools\FilterBundle\Event\Request\ProductProjectionSearchRequestEvent;
use BestIt\Commercetools\FilterBundle\Event\Request\ProductsSuggestRequestEvent;
use BestIt\Commercetools\FilterBundle\Exception\ApiException;
use BestIt\Commercetools\FilterBundle\Manager\SuggestManager;
use BestIt\Commercetools\FilterBundle\Manager\SuggestManagerInterface;
use BestIt\Commercetools\FilterBundle\Model\Fuzzy\FuzzyConfig;
use BestIt\Commercetools\FilterBundle\Model\Suggest\KeywordsResult;
use BestIt\Commercetools\FilterBundle\Model\Suggest\SuggestConfig;
use BestIt\Commercetools\FilterBundle\Model\Suggest\SuggestResult;
use BestIt\Commercetools\FilterBundle\Normalizer\ProductNormalizerInterface;
use BestIt\Commercetools\FilterBundle\SuggestEvent;
use Commercetools\Core\Client;
use Commercetools\Core\Config;
use Commercetools\Core\Model\Common\Context;
use Commercetools\Core\Model\Common\LocalizedString;
use Commercetools\Core\Model\Product\LocalizedSuggestionCollection;
use Commercetools\Core\Model\Product\ProductProjection;
use Commercetools\Core\Model\Product\ProductProjectionCollection;
use Commercetools\Core\Model\Product\Suggestion;
use Commercetools\Core\Model\Product\SuggestionCollection;
use Commercetools\Core\Model\Product\SuggestionResult;
use Commercetools\Core\Request\Products\ProductProjectionSearchRequest;
use Commercetools\Core\Request\Products\ProductsSuggestRequest;
use Commercetools\Core\Response\ApiResponseInterface;
use Commercetools\Core\Response\ErrorResponse;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class FilterManagerTest
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Manager
 * @version    $id$
 */
class SuggestManagerTest extends TestCase
{
    /**
     * The suggest manager
     *
     * @var SuggestManager
     */
    private $fixture;

    /**
     * The product normalizer
     *
     * @var ProductNormalizerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $productNormalizer;

    /**
     * The client
     *
     * @var Client|PHPUnit_Framework_MockObject_MockObject
     */
    private $client;

    /**
     * The event dispatcher
     *
     * @var EventDispatcherInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $eventDispatcher;

    /**
     * @var SuggestConfig|PHPUnit_Framework_MockObject_MockObject
     */
    private $config;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new SuggestManager(
            $this->client = static::createMock(Client::class),
            $this->productNormalizer = static::createMock(ProductNormalizerInterface::class),
            $this->eventDispatcher = static::createMock(EventDispatcherInterface::class),
            $this->config = new SuggestConfig()
        );
    }

    /**
     * Test get keywords
     *
     * @return void
     */
    public function testGetKeywords()
    {
        $keyword = 'foobar';
        $max = 10;

        $request = ProductsSuggestRequest::ofKeywords(LocalizedString::ofLangAndText('de', $keyword));
        $request->fuzzy(true);
        $request->limit($max);

        $this->eventDispatcher
            ->expects(static::once())
            ->method('dispatch')
            ->with(
                static::equalTo(SuggestEvent::KEYWORDS_REQUEST_POST),
                static::isInstanceOf(ProductsSuggestRequestEvent::class)
            );

        $this->config->getFuzzyConfig()->setIsActive(true);

        $suggestResult = new SuggestionResult();
        $localizedSuggestionCollection = new LocalizedSuggestionCollection();
        $suggestionCollection = new SuggestionCollection();
        $localizedSuggestionCollection->add($suggestionCollection);
        $suggestResult->setSearchKeywords($localizedSuggestionCollection);

        $response = $this->createMock(ApiResponseInterface::class);
        $response
            ->expects(static::once())
            ->method('toObject')
            ->willReturn($suggestResult);

        // Suggestion
        $suggestion = new Suggestion();
        $suggestion->setText('foo');
        $suggestionCollection->add($suggestion);

        $suggestion = new Suggestion();
        $suggestion->setText('bar');
        $suggestionCollection->add($suggestion);

        $this->client
            ->expects(static::once())
            ->method('execute')
            ->with(static::equalTo($request))
            ->willReturn($response);

        $result = $this->fixture->getKeywords($keyword, $max);
        static::assertInstanceOf(KeywordsResult::class, $result);
        static::assertEquals([
            'foo' => 'foo',
            'bar' => 'bar'
        ], $result->getKeywords());
        static::assertInstanceOf(ApiResponseInterface::class, $result->getHttpResponse());
    }

    /**
     * Test that the getKeywords method throw exception
     *
     * @return void
     */
    public function testGetKeywordsThrowException()
    {
        $this->expectException(ApiException::class);

        $this->client
            ->expects(static::once())
            ->method('execute')
            ->willReturn($response = static::createMock(ErrorResponse::class));

        $response
            ->expects(static::once())
            ->method('getMessage')
            ->willReturn('Foo');

        $response
            ->expects(static::once())
            ->method('isError')
            ->willReturn(true);

        $response
            ->expects(static::once())
            ->method('getStatusCode')
            ->willReturn(55);

        $this->fixture->getKeywords('foobar', 20);
    }

    /**
     * Test get products
     *
     * @return void
     */
    public function testGetProducts()
    {
        $keyword = 'foobar';
        $max = 10;

        $request = ProductProjectionSearchRequest::of();
        $request->addParam('text.de', $keyword);
        $request->fuzzy(4);
        $request->limit($max);
        $request->markMatchingVariants(true);

        $this->eventDispatcher
            ->expects(static::once())
            ->method('dispatch')
            ->with(
                static::equalTo(SuggestEvent::PRODUCTS_REQUEST_POST),
                static::isInstanceOf(ProductProjectionSearchRequestEvent::class)
            );

        $this->config->getFuzzyConfig()->setIsActive(true);
        $this->config->getFuzzyConfig()->setLevel(4);
        $this->config->setMatchVariants(true);

        $productProjectionCollection = new ProductProjectionCollection();

        $product = new ProductProjection();
        $productProjectionCollection->add($product);

        $product2 = new ProductProjection();
        $productProjectionCollection->add($product2);

        $response = $this->createMock(ApiResponseInterface::class);
        $response
            ->expects(static::once())
            ->method('toObject')
            ->willReturn($productProjectionCollection);

        $this->productNormalizer
            ->expects(static::exactly(2))
            ->method('normalize')
            ->withConsecutive([$product], [$product2])
            ->willReturnOnConsecutiveCalls(['foo' => 'bar'], ['bar' => 'foo']);

        $this->client
            ->expects(static::once())
            ->method('execute')
            ->with(static::equalTo($request))
            ->willReturn($response);

        $result = $this->fixture->getProducts($keyword, $max);
        static::assertInstanceOf(SuggestResult::class, $result);
        static::assertEquals([['foo' => 'bar'], ['bar' => 'foo']], $result->getProducts());
        static::assertInstanceOf(ApiResponseInterface::class, $result->getHttpResponse());
    }

    /**
     * Test that the getProducts method throw exception
     *
     * @return void
     */
    public function testGetProductsThrowException()
    {
        $this->expectException(ApiException::class);

        $this->client
            ->expects(static::once())
            ->method('execute')
            ->willReturn($response = static::createMock(ErrorResponse::class));

        $response
            ->expects(static::once())
            ->method('getMessage')
            ->willReturn('Foo');

        $response
            ->expects(static::once())
            ->method('isError')
            ->willReturn(true);

        $response
            ->expects(static::once())
            ->method('getStatusCode')
            ->willReturn(55);

        $this->fixture->getProducts('foobar', 20);
    }

    /**
     * Test that service implement interface
     *
     * @return void
     */
    public function testImplementInterface()
    {
        static::assertInstanceOf(SuggestManagerInterface::class, $this->fixture);
    }
}
