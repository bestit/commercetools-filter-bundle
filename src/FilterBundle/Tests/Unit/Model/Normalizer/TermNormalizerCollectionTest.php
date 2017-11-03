<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Model\Normalizer;

use ArrayIterator;
use BestIt\Commercetools\FilterBundle\Model\Normalizer\TermNormalizerCollection;
use BestIt\Commercetools\FilterBundle\Normalizer\TermNormalizerInterface;
use IteratorAggregate;
use PHPUnit\Framework\TestCase;

/**
 * Test the TermNormalizerCollection
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle\Tests\Unit\Model\Facet
 */
class TermNormalizerCollectionTest extends TestCase
{
    /**
     * The model to test
     *
     * @var TermNormalizerCollection
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new TermNormalizerCollection();
    }

    /**
     * Test add facet
     *
     * @return void
     */
    public function testAdd()
    {
        $value = $this->createMock(TermNormalizerInterface::class);

        self::assertEquals($this->fixture, $this->fixture->add($value));
        self::assertCount(1, $this->fixture->all());
    }

    /**
     * Test get iterator
     *
     * @return void
     */
    public function testGetIterator()
    {
        self::assertInstanceOf(ArrayIterator::class, $this->fixture->getIterator());
    }

    /**
     * Test iterator implementation
     *
     * @return void
     */
    public function testImplementsIteratorInterface()
    {
        self::assertInstanceOf(IteratorAggregate::class, $this->fixture);
    }

    /**
     * Test get all normalizers
     *
     * @return void
     */
    public function testAll()
    {
        $this->fixture->add($first = $this->createMock(TermNormalizerInterface::class));
        $this->fixture->add($second = $this->createMock(TermNormalizerInterface::class));
        $this->fixture->add($third = $this->createMock(TermNormalizerInterface::class));

        self::assertCount(3, $this->fixture->all());
        self::assertSame($first, $this->fixture->all()[0]);
        self::assertSame($second, $this->fixture->all()[1]);
        self::assertSame($third, $this->fixture->all()[2]);
    }
}
