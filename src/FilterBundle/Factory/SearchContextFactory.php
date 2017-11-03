<?php

namespace BestIt\Commercetools\FilterBundle\Factory;

use BestIt\Commercetools\FilterBundle\Exception\ApiException;
use BestIt\Commercetools\FilterBundle\Form\FilterType;
use BestIt\Commercetools\FilterBundle\Generator\FilterUrlGeneratorInterface;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchConfig;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchContext;
use BestIt\Commercetools\FilterBundle\Repository\CategoryRepository;
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
     * The category respoitory
     *
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * SearchContextFactory constructor.
     *
     * @param SearchConfig $config
     * @param FilterUrlGeneratorInterface $filterUrlGenerator
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        SearchConfig $config,
        FilterUrlGeneratorInterface $filterUrlGenerator,
        CategoryRepository $categoryRepository
    ) {
        $this->config = $config;
        $this->filterUrlGenerator = $filterUrlGenerator;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Create the context from request
     *
     * @param Request $request
     * @param Category $category
     * @param string $language
     *
     * @return SearchContext
     */
    public function createFromCategory(Request $request, Category $category, string $language): SearchContext
    {
        $filter = $request->get('filter', []);

        $page = 1;
        if (array_key_exists(FilterType::FIELDNAME_PAGE, $filter) && $filter[FilterType::FIELDNAME_PAGE] !== '') {
            $page = $filter[FilterType::FIELDNAME_PAGE];
        }

        $view = $this->config->getDefaultView();
        if (array_key_exists(FilterType::FIELDNAME_VIEW, $filter) && $filter[FilterType::FIELDNAME_VIEW] !== '') {
            $view = $filter[FilterType::FIELDNAME_VIEW];
        }

        $sorting = $this->config->getDefaultSorting();
        if (array_key_exists(FilterType::FIELDNAME_SORTING, $filter) && $filter[FilterType::FIELDNAME_SORTING] !== '') {
            $sorting = $filter[FilterType::FIELDNAME_SORTING];
        }

        $context = new SearchContext(
            [
                'page' => (int)$page,
                'view' => (string)$view,
                'sorting' => (string)$sorting,
                'query' => $request->query->all(),
                'config' => $this->config,
                'route' => $category,
                'baseUrl' => $this->filterUrlGenerator->generateByCategory($request, $category),
                'category' => $category,
                'language' => $language
            ]
        );

        return $context;
    }

    /**
     * Create the context from request
     *
     * @param Request $request
     * @param string $language
     * @param string|null $search
     *
     * @return SearchContext
     *
     * @throws ApiException
     */
    public function createFromSearch(Request $request, string $language, string $search = null): SearchContext
    {
        $filter = $request->get('filter', []);

        $page = 1;
        if (array_key_exists(FilterType::FIELDNAME_PAGE, $filter) && $filter[FilterType::FIELDNAME_PAGE] !== '') {
            $page = $filter[FilterType::FIELDNAME_PAGE];
        }

        $view = $this->config->getDefaultView();
        if (array_key_exists(FilterType::FIELDNAME_VIEW, $filter) && $filter[FilterType::FIELDNAME_VIEW] !== '') {
            $view = $filter[FilterType::FIELDNAME_VIEW];
        }

        $sorting = $this->config->getDefaultSorting();
        if (array_key_exists(FilterType::FIELDNAME_SORTING, $filter) && $filter[FilterType::FIELDNAME_SORTING] !== '') {
            $sorting = $filter[FilterType::FIELDNAME_SORTING];
        }

        $context = new SearchContext(
            [
                'page' => (int)$page,
                'view' => (string)$view,
                'sorting' => (string)$sorting,
                'query' => $request->query->all(),
                'config' => $this->config,
                'route' => 'search_index',
                'baseUrl' => $this->filterUrlGenerator->generateBySearch($request, $search),
                'search' => $search,
                'language' => $language
            ]
        );

        if ($query = $this->config->getBaseCategoryQuery()) {
            if ($category = $this->categoryRepository->findOneBy($query)) {
                $context->setCategory($category);
            } else {
                throw new ApiException(sprintf('Could not fetch any base category by query: "%s"', $query));
            }
        }

        return $context;
    }
}
