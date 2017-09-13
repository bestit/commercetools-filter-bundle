<?php

namespace BestIt\Commercetools\FilterBundle\Factory;

use BestIt\Commercetools\FilterBundle\Model\Search\SearchContext;
use BestIt\Commercetools\FilterBundle\Model\Sorting\Sorting;
use BestIt\Commercetools\FilterBundle\Model\Sorting\SortingCollection;

/**
 * Factory for sorting data
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 */
class SortingFactory
{
    /**
     * Create sorting collection by context
     *
     * @param SearchContext $context
     *
     * @return SortingCollection
     */
    public function create(SearchContext $context): SortingCollection
    {
        $collection = new SortingCollection();
        foreach ($context->getConfig()->getSortings() as $key => $item) {
            $collection->addSorting($sorting = new Sorting($key, $item['translation'], $item['query']));

            if ($key === $context->getConfig()->getDefaultSorting()) {
                $collection->setDefault($sorting);
            }

            if ($key === $context->getSorting()) {
                $collection->setActive($sorting);
            }
        }

        return $collection;
    }
}
