<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Model\Search;

use BestIt\Commercetools\FilterBundle\Model\Search\SearchContext;
use BestIt\Commercetools\FilterBundle\Model\Pagination\Pagination;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchResult;
use BestIt\Commercetools\FilterBundle\Model\Sorting\SortingCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormView;

/**
 * Class ResultTest
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model\Search
 * @version    $id$
 */
class SearchResultTest extends TestCase
{
    /**
     * The model to test
     *
     * @var SearchResult
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new SearchResult();
    }

    /**
     * Test setter / getter for context property
     *
     * @return void
     */
    public function testSetAndGetContext()
    {
        $value = new SearchContext();

        self::assertEquals($this->fixture, $this->fixture->setContext($value));
        self::assertEquals($value, $this->fixture->getContext());
    }

    /**
     * Test setter / getter for form property
     *
     * @return void
     */
    public function testSetAndGetForm()
    {
        $value = new FormView();

        self::assertEquals($this->fixture, $this->fixture->setForm($value));
        self::assertEquals($value, $this->fixture->getForm());
    }

    /**
     * Test setter / getter for pagination property
     *
     * @return void
     */
    public function testSetAndGetPagination()
    {
        $value = new Pagination();

        self::assertEquals($this->fixture, $this->fixture->setPagination($value));
        self::assertEquals($value, $this->fixture->getPagination());
    }

    /**
     * Test setter / getter for products property
     *
     * @return void
     */
    public function testSetAndGetProducts()
    {
        $value = ['foo' => 'bar'];

        self::assertEquals($this->fixture, $this->fixture->setProducts($value));
        self::assertEquals($value, $this->fixture->getProducts());
    }

    /**
     * Test setter / getter for sorting property
     *
     * @return void
     */
    public function testSetAndGetSorting()
    {
        $value = new SortingCollection();

        self::assertEquals($this->fixture, $this->fixture->setSorting($value));
        self::assertEquals($value, $this->fixture->getSorting());
    }

    /**
     * Test setter / getter for total products property
     *
     * @return void
     */
    public function testSetAndGetTotalProducts()
    {
        $value = 3;

        self::assertEquals($this->fixture, $this->fixture->setTotalProducts($value));
        self::assertEquals($value, $this->fixture->getTotalProducts());
    }
}
