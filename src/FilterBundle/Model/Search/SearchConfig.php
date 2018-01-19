<?php

namespace BestIt\Commercetools\FilterBundle\Model\Search;

use BestIt\Commercetools\FilterBundle\Model\Fuzzy\FuzzyConfig;

/**
 * Config data for filter bundle
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model\Pagination
 */
class SearchConfig
{
    /**
     * Items per page
     *
     * @var int
     */
    private $itemsPerPage;

    /**
     * Name of default view (eg. grid, list)
     *
     * @var string
     */
    private $defaultView;

    /**
     * Name of default sorting (eg. name_asc)
     *
     * @var string
     */
    private $defaultSorting;

    /**
     * Name of default sorting for the listing (eg. name_asc)
     *
     * @var string
     */
    private $defaultSortingListing;

    /**
     * Name of default sorting for the search (eg. name_asc)
     *
     * @var string
     */
    private $defaultSortingSearch;

    /**
     * Amount of neighbours at pagination
     *
     * @var int
     */
    private $neighbours;

    /**
     * Available sortings
     *
     * @var array
     */
    private $sortings;

    /**
     * Available facet config values
     *
     * @var array
     */
    private $facet;

    /**
     * Used translation domain
     *
     * @var string
     */
    private $translationDomain;

    /**
     * The fuzzy config
     *
     * @var FuzzyConfig
     */
    private $fuzzyConfig;

    /**
     * Match variants
     *
     * @var bool
     */
    private $matchVariants = false;

    /**
     * Optional base category query
     *
     * @var string|null
     */
    private $baseCategoryQuery;

    /**
     * Config constructor
     *
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $this->fuzzyConfig = new FuzzyConfig();

        foreach ($values as $key => $value) {
            $setter = sprintf('set%s', ucfirst($key));
            if (property_exists($this, $key) && method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }

    /**
     * Get defaultSorting
     *
     * @return string
     */
    public function getDefaultSorting(): string
    {
        return $this->defaultSorting;
    }

    /**
     * Get defaultView
     *
     * @return string
     */
    public function getDefaultView(): string
    {
        return $this->defaultView;
    }

    /**
     * Get DefaultSortingListing
     *
     * @return string
     */
    public function getDefaultSortingListing(): string
    {
        return $this->defaultSortingListing;
    }

    /**
     * Get DefaultSortingSearch
     *
     * @return string
     */
    public function getDefaultSortingSearch(): string
    {
        return $this->defaultSortingSearch;
    }

    /**
     * Get itemsPerPage
     *
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * Get neighbours
     *
     * @return int
     */
    public function getNeighbours(): int
    {
        return $this->neighbours;
    }

    /**
     * Get sortings
     *
     * @return array
     */
    public function getSortings(): array
    {
        return $this->sortings;
    }

    /**
     * Set defaultSorting
     *
     * @param string $defaultSorting
     *
     * @return SearchConfig
     */
    public function setDefaultSorting(string $defaultSorting): SearchConfig
    {
        $this->defaultSorting = $defaultSorting;

        return $this;
    }

    /**
     * Set defaultView
     *
     * @param string $defaultView
     *
     * @return SearchConfig
     */
    public function setDefaultView(string $defaultView): SearchConfig
    {
        $this->defaultView = $defaultView;

        return $this;
    }

    /**
     * Set DefaultSortingListing
     *
     * @param string $defaultSortingListing
     * @return self
     */
    public function setDefaultSortingListing(string $defaultSortingListing): SearchConfig
    {
        $this->defaultSortingListing = $defaultSortingListing;
        return $this;
    }

    /**
     * Set DefaultSortingSearch
     *
     * @param string $defaultSortingSearch
     * @return self
     */
    public function setDefaultSortingSearch(string $defaultSortingSearch): SearchConfig
    {
        $this->defaultSortingSearch = $defaultSortingSearch;
        return $this;
    }

    /**
     * Set itemsPerPage
     *
     * @param int $itemsPerPage
     *
     * @return SearchConfig
     */
    public function setItemsPerPage(int $itemsPerPage): SearchConfig
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

    /**
     * Set neighbours
     *
     * @param int $neighbours
     *
     * @return SearchConfig
     */
    public function setNeighbours(int $neighbours): SearchConfig
    {
        $this->neighbours = $neighbours;

        return $this;
    }

    /**
     * Set sortings
     *
     * @param array $sortings
     *
     * @return SearchConfig
     */
    public function setSortings(array $sortings): SearchConfig
    {
        $this->sortings = $sortings;

        return $this;
    }

    /**
     * Get facet
     *
     * @return array
     */
    public function getFacet(): array
    {
        return $this->facet;
    }

    /**
     * Set facet
     *
     * @param array $facet
     *
     * @return SearchConfig
     */
    public function setFacet(array $facet): SearchConfig
    {
        $this->facet = $facet;

        return $this;
    }

    /**
     * Get translationDomain
     *
     * @return string
     */
    public function getTranslationDomain(): string
    {
        return $this->translationDomain;
    }

    /**
     * Set translationDomain
     *
     * @param string $translationDomain
     *
     * @return SearchConfig
     */
    public function setTranslationDomain(string $translationDomain): SearchConfig
    {
        $this->translationDomain = $translationDomain;

        return $this;
    }

    /**
     * Get fuzzyConfig
     *
     * @return FuzzyConfig
     */
    public function getFuzzyConfig(): FuzzyConfig
    {
        return $this->fuzzyConfig;
    }

    /**
     * Set fuzzyConfig
     *
     * @param FuzzyConfig $fuzzyConfig
     *
     * @return SearchConfig
     */
    public function setFuzzyConfig(FuzzyConfig $fuzzyConfig): SearchConfig
    {
        $this->fuzzyConfig = $fuzzyConfig;

        return $this;
    }

    /**
     * Get matchVariants
     *
     * @return bool
     */
    public function isMatchVariants(): bool
    {
        return $this->matchVariants;
    }

    /**
     * Set matchVariants
     *
     * @param bool $matchVariants
     *
     * @return SearchConfig
     */
    public function setMatchVariants(bool $matchVariants): SearchConfig
    {
        $this->matchVariants = $matchVariants;

        return $this;
    }

    /**
     * Get baseCategoryQuery
     *
     * @return null|string
     */
    public function getBaseCategoryQuery()
    {
        return $this->baseCategoryQuery;
    }

    /**
     * Set baseCategoryQuery
     *
     * @param null|string $baseCategoryQuery
     *
     * @return SearchConfig
     */
    public function setBaseCategoryQuery(string $baseCategoryQuery = null): SearchConfig
    {
        $this->baseCategoryQuery = $baseCategoryQuery;

        return $this;
    }
}
