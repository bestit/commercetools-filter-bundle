<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Manager;

use BestIt\Commercetools\FilterBundle\Helper\EnumAttributeHelper;
use Commercetools\Core\Client;
use Commercetools\Core\Model\Common\Context;
use Commercetools\Core\Model\ProductType\AttributeDefinition;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Model\ProductType\ProductTypeCollection;
use Commercetools\Core\Model\Type\EnumType;
use Commercetools\Core\Model\Type\LocalizedEnumType;
use Commercetools\Core\Request\ProductTypes\ProductTypeQueryRequest;
use Commercetools\Core\Response\PagedQueryResponse;
use PHPUnit\Framework\TestCase;

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

        return new EnumAttributeHelper(
            $mocks['clientMock']
        );
    }

    /**
     * Test for get label function.
     *
     * @return void
     */
    public function testGetLabels()
    {
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

        $fixture = $this->createFixture(['clientMock' => $commerceToolsClient]);

        self::assertEquals(['Key1' => 'Wert1', 'Key2' => 'Wert2'], $fixture->getLabels('LENUM'));
        self::assertEquals(['Key3' => 'Wert3'], $fixture->getLabels('ENUM'));
    }
}
