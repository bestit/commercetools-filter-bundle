<?php

namespace BestIt\Commercetools\FilterBundle\Manager;

use BestIt\Commercetools\FilterBundle\Model\Search\SearchResult;
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
     * @param string $language
     *
     * @return SearchResult
     */
    public function listing(Request $request, Category $category, string $language = 'de'): SearchResult;

    /**
     * Perform a search request
     *
     * @param Request $request
     * @param string $search
     * @param string $language
     *
     * @return SearchResult
     */
    public function search(Request $request, string $search = null, string $language = 'de'): SearchResult;
}
