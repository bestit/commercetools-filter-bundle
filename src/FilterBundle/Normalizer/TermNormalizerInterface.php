<?php

namespace BestIt\Commercetools\FilterBundle\Normalizer;

use BestIt\Commercetools\FilterBundle\Exception\SkipTermException;
use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfig;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchContext;
use BestIt\Commercetools\FilterBundle\Model\Term\Term;

/**
 * Normalizer interface for terms
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle\Normalizer
 */
interface TermNormalizerInterface
{
    /**
     * Normalize term
     *
     * @param FacetConfig $config
     * @param Term $term
     * @param SearchContext $context
     *
     * @throws SkipTermException
     *
     * @return Term
     */
    public function normalize(FacetConfig $config, Term $term, SearchContext $context): Term;

    /**
     * Check if normalizer support given term type
     *
     * @param string $type
     *
     * @return bool
     */
    public function support(string $type): bool;
}
