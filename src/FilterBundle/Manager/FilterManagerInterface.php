<?php

namespace BestIt\Commercetools\FilterBundle\Manager;

use BestIt\Commercetools\FilterBundle\Model\Result;
use Commercetools\Core\Model\Category\Category;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface for filter products
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Manager
 */
interface FilterManagerInterface
{
    /**
     * Perform a listing request
     *
     * @param Request $request
     * @param Category $category
     *
     * @return Result
     */
    public function listing(Request $request, Category $category): Result;

    /**
     * Perform a search request
     *
     * @param Request $request
     * @param string $search
     *
     * @return Result
     */
    public function search(Request $request, string $search = null): Result;
}
