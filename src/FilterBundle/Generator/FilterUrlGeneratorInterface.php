<?php

namespace BestIt\Commercetools\FilterBundle\Generator;

use Commercetools\Core\Model\Category\Category;
use Symfony\Component\HttpFoundation\Request;

/**
 * Filter url generator interface
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Generator
 */
interface FilterUrlGeneratorInterface
{
    /**
     * Generate route by category
     *
     * @param Request $request
     * @param Category $category
     *
     * @return string
     */
    public function generateByCategory(Request $request, Category $category): string;

    /**
     * Generate route by category
     *
     * @param Request $request
     * @param string|null $search
     *
     * @return string
     */
    public function generateBySearch(Request $request, string $search = null): string;
}
