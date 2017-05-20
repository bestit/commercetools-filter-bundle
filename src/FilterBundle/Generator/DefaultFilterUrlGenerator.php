<?php

namespace BestIt\Commercetools\FilterBundle\Generator;

use Commercetools\Core\Model\Category\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Default filter url generator
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Generator
 */
class DefaultFilterUrlGenerator implements FilterUrlGeneratorInterface
{
    /**
     * The url generator
     *
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * DefaultFilterUrlGenerator constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->setUrlGenerator($urlGenerator);
    }

    /**
     * Get urlGenerator
     *
     * @return UrlGeneratorInterface
     */
    private function getUrlGenerator(): UrlGeneratorInterface
    {
        return $this->urlGenerator;
    }

    /**
     * Set urlGenerator
     *
     * @param UrlGeneratorInterface $urlGenerator
     *
     * @return DefaultFilterUrlGenerator
     */
    private function setUrlGenerator(UrlGeneratorInterface $urlGenerator): DefaultFilterUrlGenerator
    {
        $this->urlGenerator = $urlGenerator;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function generateByCategory(Request $request, Category $category): string
    {
        return $this->getUrlGenerator()->generate($category);
    }

    /**
     * {@inheritdoc}
     */
    public function generateBySearch(Request $request, string $search = null): string
    {
        return $this->getUrlGenerator()->generate('search_index', ['search' => $search]);
    }
}
