<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Factory;

use BestIt\Commercetools\FilterBundle\Factory\PaginationFactory;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchConfig;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchContext;
use BestIt\Commercetools\FilterBundle\Model\Pagination\Pagination;
use PHPUnit\Framework\TestCase;

/**
 * Test for pagination factory
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 * @version    $id$
 */
class PaginationFactoryTest extends TestCase
{
    /**
     * The pagination factory
     *
     * @var PaginationFactory
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new PaginationFactory();
    }

    /**
     * Test create pagination
     *
     * @return void
     */
    public function testCreate()
    {
        $config = (new SearchConfig())
            ->setItemsPerPage(20)
            ->setNeighbours(2);

        $context = (new SearchContext())
            ->setPage(4)
            ->setRoute('home_index')
            ->setBaseUrl('http://foo')
            ->setConfig($config);

        $result = $this->fixture->create($context, 130);

        static::assertInstanceOf(Pagination::class, $result);
        static::assertEquals(1, $result->getFirstPage());
        static::assertEquals(7, $result->getLastPage());
        static::assertEquals(4, $result->getCurrentPage());
        static::assertEquals([2, 3], $result->getPreviousPages());
        static::assertEquals([5, 6], $result->getNextPages());
        static::assertEquals(7, $result->getTotalPages());
    }
}
