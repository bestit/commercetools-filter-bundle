<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Manager;

use BestIt\Commercetools\FilterBundle\Helper\EnumAttributeHelper;
use Commercetools\Core\Client;
use Commercetools\Core\Model\Common\Context;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Model\ProductType\ProductTypeCollection;
use Commercetools\Core\Request\ProductTypes\ProductTypeQueryRequest;
use Commercetools\Core\Response\PagedQueryResponse;
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
     * Create a test fixture with default mocks or with overridden mocks.
     *
     * @param array $mocks Array with mocks to override.
     *
     * @return EnumAttributeHelper
     */
    private function createFixture(array $mocks = []): EnumAttributeHelper
    {
        if (!array_key_exists('clientMock', $mocks)
            || !$mocks['clientMock'] instanceof Client
        ) {
            $mocks['clientMock'] = $this->createMock(Client::class);
        }

        if (!array_key_exists('cachePoolItemInterfaceMock', $mocks)
            || !$mocks['cachePoolItemInterfaceMock'] instanceof CacheItemPoolInterface
        ) {
            $mocks['cachePoolItemInterfaceMock'] = $this->createMock(CacheItemPoolInterface::class);
        }

        if (!array_key_exists('cacheLifeTime', $mocks)
            || !is_int($mocks['cacheLifeTime'])
        ) {
            $mocks['cacheLifeTime'] = random_int(10000, 99999);
        }

        return new EnumAttributeHelper(
            $mocks['clientMock'],
            $mocks['cachePoolItemInterfaceMock'],
            $mocks['cacheLifeTime']
        );
    }

    /**
     * Test for get label function.
     *
     * @return void
     */
    public function testGetLabels()
    {
        $attributeName1 = 'LENUM';
        $attributeName2 = 'ENUM';

        $productType = ProductType::fromArray(
            json_decode(file_get_contents(__DIR__ . '/../../Fixtures/ProductTypes.json'), true)
        );

        $productTypeCollection = new ProductTypeCollection();
        $productTypeCollection->setContext(Context::of()->setLocale('de')->setLanguages(['de']));
        $productTypeCollection->add($productType);

        $responseMock = $this->createMock(PagedQueryResponse::class);
        $responseMock
            ->method('toObject')
            ->willReturn($productTypeCollection);

        $commerceToolsClient = $this->createMock(Client::class);
        $commerceToolsClient
            ->method('execute')
            ->with(self::callback(function (ProductTypeQueryRequest $args) {
                return $args instanceof ProductTypeQueryRequest;
            }))
            ->willReturn($responseMock);

        $cacheLifeTime = random_int(10000, 90000);

        $cachePool = $this->createMock(CacheItemPoolInterface::class);

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

        $cachePool
            ->method('getItem')
            ->willReturnCallback(
                function (string $name) use ($attributeName1, $cacheItem1, $attributeName2, $cacheItem2) {
                    switch ($name) {
                        case EnumAttributeHelper::CACHE_KEY . '-' . md5($attributeName1):
                            return $cacheItem1;
                        case EnumAttributeHelper::CACHE_KEY . '-' . md5($attributeName2):
                            return $cacheItem2;
                    }

                    return false;
                }
            );

        $fixture = $this->createFixture(
            [
                'clientMock' => $commerceToolsClient,
                'cachePoolItemInterfaceMock' => $cachePool,
                'cacheLifeTime' => $cacheLifeTime
            ]
        );

        self::assertEquals(['Key1' => 'Wert1', 'Key2' => 'Wert2'], $fixture->getLabels($attributeName1));
        self::assertEquals(['Key3' => 'Wert3'], $fixture->getLabels($attributeName2));
    }
}
