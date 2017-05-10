<?php

namespace BestIt\Commercetools\FilterBundle\Model;

use Symfony\Component\Form\FormView;

/**
 * Result data for filter bundle
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Model
 */
class Result
{
    /**
     * @var Context
     */
    private $context;

    /**
     * Amount of articles
     * @var int
     */
    private $totalProducts = 0;

    /**
     * Array of products
     * @var array
     */
    private $products;

    /**
     * Pagination data
     * @var Pagination
     */
    private $pagination;

    /**
     * Array of sorting options
     * @var SortingCollection
     */
    private $sorting;

    /**
     * The facet forms
     * @var FormView
     */
    private $form;

    /**
     * Get context
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }

    /**
     * Get form
     * @return FormView
     */
    public function getForm(): FormView
    {
        return $this->form;
    }

    /**
     * Get pagination
     * @return Pagination
     */
    public function getPagination(): Pagination
    {
        return $this->pagination;
    }

    /**
     * Get products
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * Get sorting
     * @return SortingCollection
     */
    public function getSorting(): SortingCollection
    {
        return $this->sorting;
    }

    /**
     * Get amount of articles
     * @return int
     */
    public function getTotalProducts(): int
    {
        return $this->totalProducts;
    }

    /**
     * Set context
     * @param Context $context
     * @return Result
     */
    public function setContext(Context $context): Result
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Set form
     * @param FormView $form
     * @return Result
     */
    public function setForm(FormView $form): Result
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Set pagination
     * @param Pagination $pagination
     * @return Result
     */
    public function setPagination(Pagination $pagination): Result
    {
        $this->pagination = $pagination;

        return $this;
    }

    /**
     * Set products
     * @param array $products
     * @return Result
     */
    public function setProducts(array $products): Result
    {
        $this->products = $products;

        return $this;
    }

    /**
     * Set sorting
     * @param SortingCollection $sorting
     * @return Result
     */
    public function setSorting(SortingCollection $sorting): Result
    {
        $this->sorting = $sorting;

        return $this;
    }

    /**
     * Set amount of products
     * @param int $totalProducts
     * @return Result
     */
    public function setTotalProducts(int $totalProducts): Result
    {
        $this->totalProducts = $totalProducts;

        return $this;
    }
}
