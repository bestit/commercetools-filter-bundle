<?php

namespace BestIt\Commercetools\FilterBundle\Model\Search;

use BestIt\Commercetools\FilterBundle\Model\Pagination\Pagination;
use BestIt\Commercetools\FilterBundle\Model\Sorting\SortingCollection;
use Commercetools\Core\Response\PagedSearchResponse;
use Symfony\Component\Form\FormView;

/**
 * Result data for filter bundle
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model\Search
 */
class SearchResult
{
    /**
     * Result context
     *
     * @var SearchContext
     */
    private $context;

    /**
     * Amount of articles
     *
     * @var int
     */
    private $totalProducts = 0;

    /**
     * Array of products
     *
     * @var array
     */
    private $products = [];

    /**
     * Pagination data
     *
     * @var Pagination
     */
    private $pagination;

    /**
     * Array of sorting options
     *
     * @var SortingCollection
     */
    private $sorting;

    /**
     * The facet forms
     *
     * @var FormView
     */
    private $form;

    /**
     * The origin response from commercetools
     *
     * @var PagedSearchResponse|null
     */
    private $httpResponse;

    /**
     * SearchResult constructor.
     */
    public function __construct()
    {
        $this->pagination = new Pagination();
        $this->sorting = new SortingCollection();
        $this->form = new FormView();
    }

    /**
     * Get context
     *
     * @return SearchContext
     */
    public function getContext(): SearchContext
    {
        return $this->context;
    }

    /**
     * Get form
     *
     * @return FormView
     */
    public function getForm(): FormView
    {
        return $this->form;
    }

    /**
     * Get pagination
     *
     * @return Pagination
     */
    public function getPagination(): Pagination
    {
        return $this->pagination;
    }

    /**
     * Get products
     *
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * Get sorting
     *
     * @return SortingCollection
     */
    public function getSorting(): SortingCollection
    {
        return $this->sorting;
    }

    /**
     * Get amount of articles
     *
     * @return int
     */
    public function getTotalProducts(): int
    {
        return $this->totalProducts;
    }

    /**
     * Set context
     *
     * @param SearchContext $context
     *
     * @return SearchResult
     */
    public function setContext(SearchContext $context): SearchResult
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Set form
     *
     * @param FormView $form
     *
     * @return SearchResult
     */
    public function setForm(FormView $form): SearchResult
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Set pagination
     *
     * @param Pagination $pagination
     *
     * @return SearchResult
     */
    public function setPagination(Pagination $pagination): SearchResult
    {
        $this->pagination = $pagination;

        return $this;
    }

    /**
     * Set products
     *
     * @param array $products
     *
     * @return SearchResult
     */
    public function setProducts(array $products): SearchResult
    {
        $this->products = $products;

        return $this;
    }

    /**
     * Set sorting
     *
     * @param SortingCollection $sorting
     *
     * @return SearchResult
     */
    public function setSorting(SortingCollection $sorting): SearchResult
    {
        $this->sorting = $sorting;

        return $this;
    }

    /**
     * Set amount of products
     *
     * @param int $totalProducts
     *
     * @return SearchResult
     */
    public function setTotalProducts(int $totalProducts): SearchResult
    {
        $this->totalProducts = $totalProducts;

        return $this;
    }

    /**
     * Get httpResponse
     *
     * @return PagedSearchResponse|null
     */
    public function getHttpResponse()
    {
        return $this->httpResponse;
    }

    /**
     * Set httpResponse
     *
     * @param PagedSearchResponse|null $httpResponse
     * @return SearchResult
     */
    public function setHttpResponse(PagedSearchResponse $httpResponse = null): SearchResult
    {
        $this->httpResponse = $httpResponse;
        return $this;
    }
}
