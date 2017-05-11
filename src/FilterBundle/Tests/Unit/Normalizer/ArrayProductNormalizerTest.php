<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Normalizer;

use BestIt\Commercetools\FilterBundle\Normalizer\ProductNormalizerInterface;
use BestIt\Commercetools\FilterBundle\Normalizer\ArrayProductNormalizer;
use Commercetools\Core\Model\Product\ProductProjection;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayProductNormalizerTest
 * @author chowanski <chowanski@bestit-online.de>
 * @category Tests\Unit
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Normalizer
 * @version $id$
 */
class ArrayProductNormalizerTest extends TestCase
{
    /**
     * The normalizer to test
     * @var ArrayProductNormalizer
     */
    private $fixture;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->fixture = new ArrayProductNormalizer();
    }

    /**
     * Test that normalizer implement interface
     * @return void
     */
    public function testImplementInterface()
    {
        static::assertInstanceOf(ProductNormalizerInterface::class, $this->fixture);
    }

    /**
     * Test normalize method
     * @return void
     */
    public function testNormalize()
    {
        $projection = new ProductProjection();

        static::assertEquals($projection->toArray(), $this->fixture->normalize($projection));
    }
}
