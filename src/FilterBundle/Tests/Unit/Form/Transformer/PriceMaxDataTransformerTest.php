<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Form\Transformer;

use BestIt\Commercetools\FilterBundle\Form\Transformer\PriceMaxDataTransformer;
use PHPUnit\Framework\TestCase;

/**
 * Test for price max data transformer
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Form\Transformer
 * @version    $id$
 */
class PriceMaxDataTransformerTest extends TestCase
{
    /**
     * The transformer
     *
     * @var PriceMaxDataTransformer
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new PriceMaxDataTransformer();
    }

    /**
     * Test reverse value
     *
     * @return void
     */
    public function testReverseValue()
    {
        static::equalTo(300, $this->fixture->reverseTransform(3));
    }

    /**
     * Test transform odd value
     *
     * @return void
     */
    public function testTransformOddValue()
    {
        static::equalTo(1, $this->fixture->transform(103));
    }

    /**
     * Test transform single digit value
     *
     * @return void
     */
    public function testTransformSingleDigitValue()
    {
        static::equalTo(1, $this->fixture->transform(3));
    }

    /**
     * Test transform value
     *
     * @return void
     */
    public function testTransformValue()
    {
        static::equalTo(3, $this->fixture->transform(300));
    }
}
