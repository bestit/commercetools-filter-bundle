<?php

namespace BestIt\Commercetools\FilterBundle\Model;

use Commercetools\Core\Model\Category\Category;

/**
 * Context data for filter bundle
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Model
 */
class Context
{
    /**
     * The current page
     * @var int
     */
    private $page;

    /**
     * Current type of view (eg. grid, list)
     * @var string
     */
    private $view;

    /**
     * Type type of sort
     * @var string
     */
    private $sorting;

    /**
     * All query params
     * @var array
     */
    private $query = [];

    /**
     * Config data
     * @var Config
     */
    private $config;

    /**
     * The current route (object)
     * @var string|object
     */
    private $route;

    /**
     * The current category object or null
     * @var Category|null
     */
    private $category;

    /**
     * The search value to filter
     * @var string|null
     */
    private $search;

    /**
     * Context constructor
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
     * @return Category|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Get config
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * Get page
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * Get query
     * @return array
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * Get route
     * @return object|string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Get search
     * @return null|string
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Get sorting
     * @return string
     */
    public function getSorting(): string
    {
        return $this->sorting;
    }

    /**
     * Get view
     * @return string
     */
    public function getView(): string
    {
        return $this->view;
    }

    /**
     * Set category
     * @param Category|null $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Set config
     * @param Config $config
     * @return Context
     */
    public function setConfig(Config $config): Context
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Set page
     * @param int $page
     * @return Context
     */
    public function setPage(int $page): Context
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Set query
     * @param array $query
     * @return Context
     */
    public function setQuery(array $query): Context
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Set route
     * @param object|string $route
     * @return Context
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Set search
     * @param null|string $search
     * @return Context
     */
    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * Set sorting
     * @param string $sorting
     * @return Context
     */
    public function setSorting(string $sorting): Context
    {
        $this->sorting = $sorting;

        return $this;
    }

    /**
     * Set view
     * @param string $view
     * @return Context
     */
    public function setView(string $view): Context
    {
        $this->view = $view;

        return $this;
    }
}
