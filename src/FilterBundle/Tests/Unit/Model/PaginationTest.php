<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Model;

use BestIt\Commercetools\FilterBundle\Model\Pagination;
use PHPUnit\Framework\TestCase;

/**
 * Class PaginationTest
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model
 * @version    $id$
 */
class PaginationTest extends TestCase
{
    /**
     * The model to test
     *
     * @var Pagination
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new Pagination();
    }

    /**
     * Test setter / getter for currentPage property
     *
     * @return void
     */
    public function testSetAndGetCurrentPage()
    {
        $value = 5;

        self::assertEquals($this->fixture, $this->fixture->setCurrentPage($value));
        self::assertEquals($value, $this->fixture->getCurrentPage());
    }

    /**
     * Test setter / getter for firstPage property
     *
     * @return void
     */
    public function testSetAndGetFirstPage()
    {
        $value = 3;

        self::assertEquals($this->fixture, $this->fixture->setFirstPage($value));
        self::assertEquals($value, $this->fixture->getFirstPage());
    }

    /**
     * Test setter / getter for lastPage property
     *
     * @return void
     */
    public function testSetAndGetLastPage()
    {
        $value = 23;

        self::assertEquals($this->fixture, $this->fixture->setLastPage($value));
        self::assertEquals($value, $this->fixture->getLastPage());
    }

    /**
     * Test setter / getter for nextPages property
     *
     * @return void
     */
    public function testSetAndGetNextPages()
    {
        $value = [1, 2, 3];

        self::assertEquals($this->fixture, $this->fixture->setNextPages($value));
        self::assertEquals($value, $this->fixture->getNextPages());
    }

    /**
     * Test setter / getter for previousPages property
     *
     * @return void
     */
    public function testSetAndGetPreviousPages()
    {
        $value = [1, 2, 3];

        self::assertEquals($this->fixture, $this->fixture->setPreviousPages($value));
        self::assertEquals($value, $this->fixture->getPreviousPages());
    }

    /**
     * Test setter / getter for totalPages property
     *
     * @return void
     */
    public function testSetAndGetTotalPages()
    {
        $value = 23;

        self::assertEquals($this->fixture, $this->fixture->setTotalPages($value));
        self::assertEquals($value, $this->fixture->getTotalPages());
    }
}
