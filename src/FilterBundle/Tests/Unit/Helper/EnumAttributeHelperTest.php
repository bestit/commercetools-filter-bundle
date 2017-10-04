<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Manager;

use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfig;
use BestIt\Commercetools\FilterBundle\Model\Term\Term;
use BestIt\Commercetools\FilterBundle\Normalizer\Term\EnumAttributeNormalizer;
use Commercetools\Commons\Helper\QueryHelper;
use Commercetools\Core\Client;
use Commercetools\Core\Model\Common\Context;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Model\ProductType\ProductTypeCollection;
use Commercetools\Core\Request\ProductTypes\ProductTypeQueryRequest;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Test for class EnumAttributeHelperTest.
 *
 * @package BestIt\Commercetools\FilterBundle\Tests\Unit\Manager
 * @author Tim Kellner <tim.kellner@bestit-online.de>
 */
class EnumAttributeHelperTest extends TestCase
{
    /**
     * The ct client
     *
     * @var Client
     */
    private $client;

    /**
     * The cache pool
     *
     * @var CacheItemPoolInterface
     */
    private $cachePool;

    /**
     * The query helper
     *
     * @var QueryHelper
     */
    private $queryHelper;

    /**
     * The normalizer
     *
     * @var EnumAttributeNormalizer
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new EnumAttributeNormalizer(
            $this->client = $this->createMock(Client::class),
            $this->cachePool = $this->createMock(CacheItemPoolInterface::class),
            600,
            $this->queryHelper = $this->createMock(QueryHelper::class)
        );
    }

    /**
     * Test for get label function.
     *
     * @return void
     */
    public function testGetLabels()
    {
        $facetConfig1 = new FacetConfig();
        $facetConfig1->setField('LENUM');
        $term1 = new Term();
        $term1->setTitle('Key1');

        $facetConfig2 = new FacetConfig();
        $facetConfig2->setField('ENUM');
        $term2 = new Term();
        $term2->setTitle('Key3');

        $productType = ProductType::fromArray(
            json_decode(file_get_contents(__DIR__ . '/../../Fixtures/ProductTypes.json'), true)
        );

        $productTypeCollection = new ProductTypeCollection();
        $productTypeCollection->setContext(Context::of()->setLocale('de')->setLanguages(['de']));
        $productTypeCollection->add($productType);

        $this->queryHelper
            ->expects(static::once())
            ->method('getAll')
            ->with($this->client, static::isInstanceOf(ProductTypeQueryRequest::class))
            ->willReturn($productTypeCollection);

        $cacheItem1 = $this->createMock(CacheItemInterface::class);
        $cacheItem1
            ->method('isHit')
            ->willReturn(false);

        $cachedLabels = [
            'Key3' => 'Wert3'
        ];

        $cacheItem2 = $this->createMock(CacheItemInterface::class);
        $cacheItem2
            ->method('isHit')
            ->willReturn(true);
        $cacheItem2
            ->method('get')
            ->willReturn($cachedLabels);

        $this->cachePool
            ->method('getItem')
            ->willReturnCallback(
                function (string $name) use ($cacheItem1, $cacheItem2) {
                    switch ($name) {
                        case EnumAttributeNormalizer::CACHE_KEY . '-' . md5('LENUM'):
                            return $cacheItem1;
                        case EnumAttributeNormalizer::CACHE_KEY . '-' . md5('ENUM'):
                            return $cacheItem2;
                    }

                    return false;
                }
            );

        self::assertEquals('Wert1', $this->fixture->normalize($facetConfig1, $term1)->getTitle());
        self::assertEquals('Wert3', $this->fixture->normalize($facetConfig2, $term2)->getTitle());
    }
}
