<?php

namespace BestIt\Commercetools\FilterBundle\Model\Search;

use BestIt\Commercetools\FilterBundle\Model\Search\SearchConfig;
use Commercetools\Core\Model\Category\Category;

/**
 * Context data for filter bundle
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model\Pagination
 */
class SearchContext
{
    /**
     * The current page
     *
     * @var int
     */
    private $page;

    /**
     * Current type of view (eg. grid, list)
     *
     * @var string
     */
    private $view;

    /**
     * Type type of sort
     *
     * @var string
     */
    private $sorting;

    /**
     * All query params
     *
     * @var array
     */
    private $query = [];

    /**
     * Config data
     *
     * @var SearchConfig
     */
    private $config;

    /**
     * The current route (object)
     *
     * @var string|object
     *
     * @deprecated Will be removed. Use property 'baseUrl'
     */
    private $route;

    /**
     * The current category object or null
     *
     * @var Category|null
     */
    private $category;

    /**
     * The search value to filter
     *
     * @var string|null
     */
    private $search;

    /**
     * Generated base url
     *
     * @var string
     */
    private $baseUrl;

    /**
     * The language for searching
     *
     * @var string
     */
    private $language;

    /**
     * Context constructor
     *
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        foreach ($values as $key => $value) {
            $setter = sprintf('set%s', ucfirst($key));
            if (property_exists($this, $key) && method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }

    /**
     * Get category
     *
     * @return Category|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Get config
     *
     * @return SearchConfig
     */
    public function getConfig(): SearchConfig
    {
        return $this->config;
    }

    /**
     * Get page
     *
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * Get query
     *
     * @return array
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * Get route
     *
     * @return object|string
     *
     * @deprecated Will be removed. Use property 'baseUrl'
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Get search
     *
     * @return null|string
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Get sorting
     *
     * @return string
     */
    public function getSorting(): string
    {
        return $this->sorting;
    }

    /**
     * Get view
     *
     * @return string
     */
    public function getView(): string
    {
        return $this->view;
    }

    /**
     * Set category
     *
     * @param Category|null $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Set config
     *
     * @param SearchConfig $config
     *
     * @return SearchContext
     */
    public function setConfig(SearchConfig $config): SearchContext
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Set page
     *
     * @param int $page
     *
     * @return SearchContext
     */
    public function setPage(int $page): SearchContext
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Set query
     *
     * @param array $query
     *
     * @return SearchContext
     */
    public function setQuery(array $query): SearchContext
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Set route
     *
     * @param object|string $route
     *
     * @return SearchContext
     *
     * @deprecated Will be removed. Use property 'baseUrl'
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Set search
     *
     * @param null|string $search
     *
     * @return SearchContext
     */
    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * Set sorting
     *
     * @param string $sorting
     *
     * @return SearchContext
     */
    public function setSorting(string $sorting): SearchContext
    {
        $this->sorting = $sorting;

        return $this;
    }

    /**
     * Set view
     *
     * @param string $view
     *
     * @return SearchContext
     */
    public function setView(string $view): SearchContext
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Get baseUrl
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Set baseUrl
     *
     * @param string $baseUrl
     *
     * @return SearchContext
     */
    public function setBaseUrl(string $baseUrl): SearchContext
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return SearchContext
     */
    public function setLanguage(string $language): SearchContext
    {
        $this->language = $language;

        return $this;
    }
}
