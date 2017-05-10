<?php

namespace BestIt\Commercetools\FilterBundle\Model;

use Symfony\Component\Form\FormView;

/**
 * Response data for filter bundle
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Model
 */
class Response
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
     * @return Response
     */
    public function setContext(Context $context): Response
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Set form
     * @param FormView $form
     * @return Response
     */
    public function setForm(FormView $form): Response
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Set pagination
     * @param Pagination $pagination
     * @return Response
     */
    public function setPagination(Pagination $pagination): Response
    {
        $this->pagination = $pagination;

        return $this;
    }

    /**
     * Set products
     * @param array $products
     * @return Response
     */
    public function setProducts(array $products): Response
    {
        $this->products = $products;

        return $this;
    }

    /**
     * Set sorting
     * @param SortingCollection $sorting
     * @return Response
     */
    public function setSorting(SortingCollection $sorting): Response
    {
        $this->sorting = $sorting;

        return $this;
    }

    /**
     * Set amount of products
     * @param int $totalProducts
     * @return Response
     */
    public function setTotalProducts(int $totalProducts): Response
    {
        $this->totalProducts = $totalProducts;

        return $this;
    }
}
