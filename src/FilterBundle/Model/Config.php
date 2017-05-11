<?php

namespace BestIt\Commercetools\FilterBundle\Model;

/**
 * Config data for filter bundle
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Model
 */
class Config
{
    /**
     * Items per page
     * @var int
     */
    private $itemsPerPage;

    /**
     * Name of default view (eg. grid, list)
     * @var string
     */
    private $defaultView;

    /**
     * Name of default sorting (eg. name_asc)
     * @var string
     */
    private $defaultSorting;

    /**
     * Amount of neighbours at pagination
     * @var int
     */
    private $neighbours;

    /**+
     * The page query key
     * @var string
     */
    private $pageQueryKey;

    /**
     * The sort query key
     * @var string
     */
    private $sortQueryKey;

    /**
     * The view query key
     * @var string
     */
    private $viewQueryKey;

    /**
     * Available sortings
     * @var array
     */
    private $sortings;

    /**
     * Available facet config values
     * @var array
     */
    private $facet;

    /**
     * Used translation domain
     * @var string
     */
    private $translationDomain;

    /**
     * Config constructor
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
     * Get defaultSorting
     * @return string
     */
    public function getDefaultSorting(): string
    {
        return $this->defaultSorting;
    }

    /**
     * Get defaultView
     * @return string
     */
    public function getDefaultView(): string
    {
        return $this->defaultView;
    }

    /**
     * Get itemsPerPage
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * Get neighbours
     * @return int
     */
    public function getNeighbours(): int
    {
        return $this->neighbours;
    }

    /**
     * Get pageQueryKey
     * @return string
     */
    public function getPageQueryKey(): string
    {
        return $this->pageQueryKey;
    }

    /**
     * Get sortQueryKey
     * @return string
     */
    public function getSortQueryKey(): string
    {
        return $this->sortQueryKey;
    }

    /**
     * Get sortings
     * @return array
     */
    public function getSortings(): array
    {
        return $this->sortings;
    }

    /**
     * Get viewQueryKey
     * @return string
     */
    public function getViewQueryKey(): string
    {
        return $this->viewQueryKey;
    }

    /**
     * Set defaultSorting
     * @param string $defaultSorting
     * @return Config
     */
    public function setDefaultSorting(string $defaultSorting): Config
    {
        $this->defaultSorting = $defaultSorting;

        return $this;
    }

    /**
     * Set defaultView
     * @param string $defaultView
     * @return Config
     */
    public function setDefaultView(string $defaultView): Config
    {
        $this->defaultView = $defaultView;

        return $this;
    }

    /**
     * Set itemsPerPage
     * @param int $itemsPerPage
     * @return Config
     */
    public function setItemsPerPage(int $itemsPerPage): Config
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

    /**
     * Set neighbours
     * @param int $neighbours
     * @return Config
     */
    public function setNeighbours(int $neighbours): Config
    {
        $this->neighbours = $neighbours;

        return $this;
    }

    /**
     * Set pageQueryKey
     * @param string $pageQueryKey
     * @return Config
     */
    public function setPageQueryKey(string $pageQueryKey): Config
    {
        $this->pageQueryKey = $pageQueryKey;

        return $this;
    }

    /**
     * Set sortQueryKey
     * @param string $sortQueryKey
     * @return Config
     */
    public function setSortQueryKey(string $sortQueryKey): Config
    {
        $this->sortQueryKey = $sortQueryKey;

        return $this;
    }

    /**
     * Set sortings
     * @param array $sortings
     * @return Config
     */
    public function setSortings(array $sortings): Config
    {
        $this->sortings = $sortings;

        return $this;
    }

    /**
     * Set viewQueryKey
     * @param string $viewQueryKey
     * @return Config
     */
    public function setViewQueryKey(string $viewQueryKey): Config
    {
        $this->viewQueryKey = $viewQueryKey;

        return $this;
    }

    /**
     * Get facet
     * @return array
     */
    public function getFacet(): array
    {
        return $this->facet;
    }

    /**
     * Set facet
     * @param array $facet
     * @return Config
     */
    public function setFacet(array $facet): Config
    {
        $this->facet = $facet;
        return $this;
    }

    /**
     * Get translationDomain
     * @return string
     */
    public function getTranslationDomain(): string
    {
        return $this->translationDomain;
    }

    /**
     * Set translationDomain
     * @param string $translationDomain
     * @return Config
     */
    public function setTranslationDomain(string $translationDomain): Config
    {
        $this->translationDomain = $translationDomain;
        return $this;
    }
}
