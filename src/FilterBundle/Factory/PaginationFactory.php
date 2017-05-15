<?php

namespace BestIt\Commercetools\FilterBundle\Factory;

use BestIt\Commercetools\FilterBundle\Model\Context;
use BestIt\Commercetools\FilterBundle\Model\Pagination;
use Kilte\Pagination\Pagination as Paginator;

/**
 * Factory for pagination data
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 */
class PaginationFactory
{
    /**
     * Create sorting collection by context
     *
     * @param Context $context
     * @param int $totalItems
     *
     * @return Pagination
     */
    public function create(Context $context, int $totalItems): Pagination
    {
        $pagination = new Paginator(
            $totalItems,
            $context->getPage(),
            $context->getConfig()->getItemsPerPage(),
            $context->getConfig()->getNeighbours()
        );

        $result = [
            'firstPage' => 1,
            'lastPage' => $pagination->totalPages(),
            'currentPage' => $context->getPage(),
            'totalPages' => $pagination->totalPages(),
            'previousPages' => [],
            'nextPages' => [],
            'route' => $context->getRoute(),
            'pageQueryKey' => $context->getConfig()->getPageQueryKey()
        ];

        foreach ($pagination->build() as $page => $type) {
            switch ($type) {
                case Paginator::TAG_PREVIOUS:
                    $result['previousPages'][] = $page;
                    break;
                case Paginator::TAG_NEXT:
                    $result['nextPages'][] = $page;
                    break;
            }
        }

        return new Pagination($result);
    }
}
