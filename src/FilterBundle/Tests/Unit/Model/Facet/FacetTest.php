<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Model\Facet;

use BestIt\Commercetools\FilterBundle\Model\Facet\Facet;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfig;
use BestIt\Commercetools\FilterBundle\Model\Facet\RangeCollection;
use BestIt\Commercetools\FilterBundle\Model\Term\TermCollection;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Test facet model
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model\Facet
 * @version    $id$
 */
class FacetTest extends TestCase
{
    /**
     * The model to test
     *
     * @var Facet
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new Facet();
    }

    /**
     * Test setter / getter for config property
     *
     * @return void
     */
    public function testSetAndGetConfig()
    {
        $value = new FacetConfig();

        self::assertEquals($this->fixture, $this->fixture->setConfig($value));
        self::assertEquals($value, $this->fixture->getConfig());
    }

    /**
     * Test setter / getter for name property
     *
     * @return void
     */
    public function testSetAndGetName()
    {
        $value = 'name';

        self::assertEquals($this->fixture, $this->fixture->setName($value));
        self::assertEquals($value, $this->fixture->getName());
    }

    /**
     * Test setter / getter for ranges property
     *
     * @return void
     */
    public function testSetAndGetRanges()
    {
        $value = new RangeCollection();

        self::assertEquals($this->fixture, $this->fixture->setRanges($value));
        self::assertEquals($value, $this->fixture->getRanges());
    }

    /**
     * Test setter / getter for terms property
     *
     * @return void
     */
    public function testSetAndGetTerms()
    {
        $value = new TermCollection();

        self::assertEquals($this->fixture, $this->fixture->setTerms($value));
        self::assertEquals($value, $this->fixture->getTerms());
    }

    /**
     * Test setter / getter for total property
     *
     * @return void
     */
    public function testSetAndGetTotal()
    {
        $value = 45;

        self::assertEquals($this->fixture, $this->fixture->setTotal($value));
        self::assertEquals($value, $this->fixture->getTotal());
    }

    /**
     * Test setter / getter for type property
     *
     * @return void
     */
    public function testSetAndGetType()
    {
        $value = 'text';

        self::assertEquals($this->fixture, $this->fixture->setType($value));
        self::assertEquals($value, $this->fixture->getType());
    }

    /**
     * Setter should throw an exception with invalid value
     *
     * @return void
     */
    public function testSetTypeThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->fixture->setType('foo');
    }
}
