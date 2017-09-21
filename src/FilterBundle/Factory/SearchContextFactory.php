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
        $this
            ->setConfig($config)
            ->setFilterUrlGenerator($filterUrlGenerator);
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
        $config = $this->getConfig();

        $filter = $request->get('filter', []);

        $page = 1;
        if (array_key_exists(FilterType::FIELDNAME_PAGE, $filter) && $filter[FilterType::FIELDNAME_PAGE] !== '') {
            $page = $filter[FilterType::FIELDNAME_PAGE];
        }

        $view = $this->getConfig()->getDefaultView();
        if (array_key_exists(FilterType::FIELDNAME_VIEW, $filter) && $filter[FilterType::FIELDNAME_VIEW] !== '') {
            $view = $filter[FilterType::FIELDNAME_VIEW];
        }

        $sorting = $this->getConfig()->getDefaultSorting();
        if (array_key_exists(FilterType::FIELDNAME_SORTING, $filter) && $filter[FilterType::FIELDNAME_SORTING] !== '') {
            $sorting = $filter[FilterType::FIELDNAME_SORTING];
        }

        $context = new SearchContext(
            [
                'page' => (int) $page,
                'view' => (string) $view,
                'sorting' => (string) $sorting,
                'query' => $request->query->all(),
                'config' => $config,
                'route' => $category,
                'baseUrl' => $this->getFilterUrlGenerator()->generateByCategory($request, $category),
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
        $config = $this->getConfig();

        $filter = $request->get('filter', []);

        $page = 1;
        if (array_key_exists(FilterType::FIELDNAME_PAGE, $filter) && $filter[FilterType::FIELDNAME_PAGE] !== '') {
            $page = $filter[FilterType::FIELDNAME_PAGE];
        }

        $view = $this->getConfig()->getDefaultView();
        if (array_key_exists(FilterType::FIELDNAME_VIEW, $filter) && $filter[FilterType::FIELDNAME_VIEW] !== '') {
            $view = $filter[FilterType::FIELDNAME_VIEW];
        }

        $sorting = $this->getConfig()->getDefaultSorting();
        if (array_key_exists(FilterType::FIELDNAME_SORTING, $filter) && $filter[FilterType::FIELDNAME_SORTING] !== '') {
            $sorting = $filter[FilterType::FIELDNAME_SORTING];
        }

        $context = new SearchContext(
            [
                'page' => (int) $page,
                'view' => (string) $view,
                'sorting' => (string) $sorting,
                'query' => $request->query->all(),
                'config' => $config,
                'route' => 'search_index',
                'baseUrl' => $this->getFilterUrlGenerator()->generateBySearch($request, $search),
                'search' => $search
            ]
        );

        return $context;
    }

    /**
     * Get filterUrlGenerator
     *
     * @return FilterUrlGeneratorInterface
     */
    private function getFilterUrlGenerator(): FilterUrlGeneratorInterface
    {
        return $this->filterUrlGenerator;
    }

    /**
     * Set filterUrlGenerator
     *
     * @param FilterUrlGeneratorInterface $filterUrlGenerator
     *
     * @return SearchContextFactory
     */
    private function setFilterUrlGenerator(FilterUrlGeneratorInterface $filterUrlGenerator): SearchContextFactory
    {
        $this->filterUrlGenerator = $filterUrlGenerator;

        return $this;
    }

    /**
     * Get config
     *
     * @return SearchConfig
     */
    private function getConfig(): SearchConfig
    {
        return $this->config;
    }

    /**
     * Set config
     *
     * @param SearchConfig $config
     *
     * @return SearchContextFactory
     */
    private function setConfig(SearchConfig $config): SearchContextFactory
    {
        $this->config = $config;

        return $this;
    }
}
