<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Normalizer;

use BestIt\Commercetools\FilterBundle\Normalizer\EmptyProductNormalizer;
use BestIt\Commercetools\FilterBundle\Normalizer\ProductNormalizerInterface;
use Commercetools\Core\Model\Product\ProductProjection;
use PHPUnit\Framework\TestCase;

/**
 * Class EmptyProductNormalizerTest
 * @author chowanski <chowanski@bestit-online.de>
 * @category Tests\Unit
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Normalizer
 * @version $id$
 */
class EmptyProductNormalizerTest extends TestCase
{
    /**
     * The normalizer to test
     * @var EmptyProductNormalizer
     */
    private $fixture;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->fixture = new EmptyProductNormalizer();
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

        static::assertEquals($projection, $this->fixture->normalize($projection));
    }
}
