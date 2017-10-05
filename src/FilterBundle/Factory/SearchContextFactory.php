<?php

namespace BestIt\Commercetools\FilterBundle\Factory;

use BestIt\Commercetools\FilterBundle\Form\FilterType;
use BestIt\Commercetools\FilterBundle\Generator\FilterUrlGeneratorInterface;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchConfig;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchContext;
use Commercetools\Core\Model\Category\Category;
use Symfony\Component\HttpFoundation\Request;

/**
 * Factory for context data
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 */
class SearchContextFactory
{
    /**
     * The config
     *
     * @var SearchConfig
     */
    private $config;

    /**
     * The filter url generator
     *
     * @var FilterUrlGeneratorInterface
     */
    private $filterUrlGenerator;

    /**
     * ContextFactory constructor.
     *
     * @param SearchConfig $config
     * @param FilterUrlGeneratorInterface $filterUrlGenerator
     */
    public function __construct(SearchConfig $config, FilterUrlGeneratorInterface $filterUrlGenerator)
    {
        $this->config = $config;
        $this->filterUrlGenerator = $filterUrlGenerator;
    }

    /**
     * Create the context from request
     *
     * @param Request $request
     * @param Category $category
     *
     * @return SearchContext
     */
    public function createFromCategory(Request $request, Category $category): SearchContext
    {
        $filter = $request->get('filter', []);

        $page = 1;
        if (array_key_exists(FilterType::FIELDNAME_PAGE, $filter) && $filter[FilterType::FIELDNAME_PAGE] !== '') {
            $page = $filter[FilterType::FIELDNAME_PAGE];
        }

        $sorting = $this->config->getDefaultSorting();
        if (array_key_exists(FilterType::FIELDNAME_SORTING, $filter) && $filter[FilterType::FIELDNAME_SORTING] !== '') {
            $sorting = $filter[FilterType::FIELDNAME_SORTING];
        }

        // Now parse additional fields
        $additionalFilterFormFields = $this->config->getFilterFormFields();
        foreach ($additionalFilterFormFields as $name => $defaultValue) {
            if (array_key_exists($name, $filter) && $filter[$name] !== '') {
                $additionalFilterFormFields[$name] = $filter[$name];
            }
        }

        $context = new SearchContext(
            [
                'page' => (int)$page,
                'filterFormFields' => $additionalFilterFormFields,
                'sorting' => (string)$sorting,
                'query' => $request->query->all(),
                'config' => $this->config,
                'route' => $category,
                'baseUrl' => $this->filterUrlGenerator->generateByCategory($request, $category),
                'category' => $category
            ]
        );

        return $context;
    }

    /**
     * Create the context from request
     *
     * @param Request $request
     * @param string|null $search
     *
     * @return SearchContext
     */
    public function createFromSearch(Request $request, string $search = null): SearchContext
    {
        $filter = $request->get('filter', []);

        // Add search term to filter query
        if ($search !== null) {
            $filter['search'] = $search;
        }

        $page = 1;
        if (array_key_exists(FilterType::FIELDNAME_PAGE, $filter) && $filter[FilterType::FIELDNAME_PAGE] !== '') {
            $page = $filter[FilterType::FIELDNAME_PAGE];
        }

        $sorting = $this->config->getDefaultSorting();
        if (array_key_exists(FilterType::FIELDNAME_SORTING, $filter) && $filter[FilterType::FIELDNAME_SORTING] !== '') {
            $sorting = $filter[FilterType::FIELDNAME_SORTING];
        }

        // Now parse additional fields
        $additionalFilterFormFields = $this->config->getFilterFormFields();
        foreach ($additionalFilterFormFields as $name => $defaultValue) {
            if (array_key_exists($name, $filter) && $filter[$name] !== '') {
                $additionalFilterFormFields[$name] = $filter[$name];
            }
        }

        $context = new SearchContext(
            [
                'page' => (int)$page,
                'filterFormFields' => $additionalFilterFormFields,
                'sorting' => (string)$sorting,
                'query' => $request->query->all(),
                'config' => $this->config,
                'route' => 'search_index',
                'baseUrl' => $this->filterUrlGenerator->generateBySearch($request, $filter['search']),
                'search' => $filter['search']
            ]
        );

        return $context;
    }
}
