<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Model\Term;

use ArrayIterator;
use BestIt\Commercetools\FilterBundle\Model\Term\Term;
use BestIt\Commercetools\FilterBundle\Model\Term\TermCollection;
use IteratorAggregate;
use PHPUnit\Framework\TestCase;

/**
 * Test term collection model
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model\Term
 * @version    $id$
 */
class TermCollectionTest extends TestCase
{
    /**
     * The model to test
     *
     * @var TermCollection
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new TermCollection();
    }

    /**
     * Test add term
     *
     * @return void
     */
    public function testAddTerm()
    {
        $value = new Term();

        self::assertEquals($this->fixture, $this->fixture->addTerm($value));
        self::assertEquals(1, count($this->fixture->toArray()));
    }

    /**
     * Test count
     *
     * @return void
     */
    public function testCount()
    {
        $value = new Term();

        self::assertEquals($this->fixture, $this->fixture->addTerm($value));
        self::assertEquals($this->fixture, $this->fixture->addTerm($value));
        self::assertEquals($this->fixture, $this->fixture->addTerm($value));

        self::assertEquals(3, $this->fixture->count());
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
     * Test get sorted terms
     *
     * @return void
     */
    public function testSortedTerms()
    {
        $this->fixture->addTerm((new Term())->setTitle('yLast'));
        $this->fixture->addTerm((new Term())->setTitle('aFirst'));
        $this->fixture->addTerm((new Term())->setTitle('gMiddle'));

        self::assertEquals('aFirst', $this->fixture->toArray()[0]->getTitle());
        self::assertEquals('gMiddle', $this->fixture->toArray()[1]->getTitle());
        self::assertEquals('yLast', $this->fixture->toArray()[2]->getTitle());
    }
}
