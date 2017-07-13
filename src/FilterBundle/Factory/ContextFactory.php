<?php

namespace BestIt\Commercetools\FilterBundle\Factory;

use BestIt\Commercetools\FilterBundle\Form\FilterType;
use BestIt\Commercetools\FilterBundle\Generator\FilterUrlGeneratorInterface;
use BestIt\Commercetools\FilterBundle\Model\Config;
use BestIt\Commercetools\FilterBundle\Model\Context;
use Commercetools\Core\Model\Category\Category;
use Symfony\Component\HttpFoundation\Request;

/**
 * Factory for context data
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Factory
 */
class ContextFactory
{
    /**
     * The config
     *
     * @var Config
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
     * @param Config $config
     * @param FilterUrlGeneratorInterface $filterUrlGenerator
     */
    public function __construct(Config $config, FilterUrlGeneratorInterface $filterUrlGenerator)
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
     * @return Context
     */
    public function createFromCategory(Request $request, Category $category): Context
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

        $context = new Context(
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
     * @return Context
     */
    public function createFromSearch(Request $request, string $search = null): Context
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

        $context = new Context(
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
     * @return ContextFactory
     */
    private function setFilterUrlGenerator(FilterUrlGeneratorInterface $filterUrlGenerator): ContextFactory
    {
        $this->filterUrlGenerator = $filterUrlGenerator;

        return $this;
    }

    /**
     * Get config
     *
     * @return Config
     */
    private function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * Set config
     *
     * @param Config $config
     *
     * @return ContextFactory
     */
    private function setConfig(Config $config): ContextFactory
    {
        $this->config = $config;

        return $this;
    }
}
