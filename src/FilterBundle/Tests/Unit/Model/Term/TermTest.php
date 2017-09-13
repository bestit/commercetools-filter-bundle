<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Model\Term;

use BestIt\Commercetools\FilterBundle\Model\Pagination\Pagination;
use BestIt\Commercetools\FilterBundle\Model\Term\Term;
use PHPUnit\Framework\TestCase;

/**
 * Test the term model
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model\Term
 * @version    $id$
 */
class TermTest extends TestCase
{
    /**
     * The model to test
     *
     * @var Term
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new Term();
    }

    /**
     * Test setter / getter for count property
     *
     * @return void
     */
    public function testSetAndGetCount()
    {
        $value = 23;

        self::assertEquals(0, $this->fixture->getCount());
        self::assertEquals($this->fixture, $this->fixture->setCount($value));
        self::assertEquals($value, $this->fixture->getCount());
    }

    /**
     * Test setter / getter for term property
     *
     * @return void
     */
    public function testSetAndGetTerm()
    {
        $value = 'foobar';

        self::assertEquals(null, $this->fixture->getTerm());
        self::assertEquals($this->fixture, $this->fixture->setTerm($value));
        self::assertEquals($value, $this->fixture->getTerm());
    }

    /**
     * Test setter / getter for title property
     *
     * @return void
     */
    public function testSetAndGetTitle()
    {
        $value = 'foobar';

        self::assertEquals(null, $this->fixture->getTitle());
        self::assertEquals($this->fixture, $this->fixture->setTitle($value));
        self::assertEquals($value, $this->fixture->getTitle());
    }
}
