<?php

namespace BestIt\Commercetools\FilterBundle\Normalizer;

use BestIt\Commercetools\FilterBundle\Model\Facet\FacetConfig;
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
     *
     * @return Term
     */
    public function normalize(FacetConfig $config, Term $term) :Term;
}
