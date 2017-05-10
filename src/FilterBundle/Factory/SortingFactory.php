<?php

namespace BestIt\Commercetools\FilterBundle\Factory;

use BestIt\Commercetools\FilterBundle\Model\Context;
use BestIt\Commercetools\FilterBundle\Model\Sorting;
use BestIt\Commercetools\FilterBundle\Model\SortingCollection;

/**
 * Factory for sorting data
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 */
class SortingFactory
{
    /**
     * Create sorting collection by context
     * @param Context $context
     * @return SortingCollection
     */
    public function create(Context $context): SortingCollection
    {
        $collection = new SortingCollection();
        foreach ($context->getConfig()->getSortings() as $key => $item) {
            $collection->addSorting($sorting = new Sorting($key, $item['translation'], $item['query']));

            if ($item['default'] === true) {
                $collection->setDefault($sorting);
            }

            if ($key === $context->getSorting()) {
                $collection->setActive($sorting);
            }
        }

        return $collection;
    }
}
