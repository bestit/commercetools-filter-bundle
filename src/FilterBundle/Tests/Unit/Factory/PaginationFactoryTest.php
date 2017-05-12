<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Factory;

use BestIt\Commercetools\FilterBundle\Factory\PaginationFactory;
use BestIt\Commercetools\FilterBundle\Model\Config;
use BestIt\Commercetools\FilterBundle\Model\Context;
use BestIt\Commercetools\FilterBundle\Model\Pagination;
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
        $config = (new Config())
            ->setItemsPerPage(20)
            ->setNeighbours(2)
            ->setPageQueryKey('p');

        $context = (new Context())
            ->setPage(4)
            ->setRoute('home_index')
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
