<?php

namespace BestIt\Commercetools\FilterBundle\Normalizer;

use Commercetools\Core\Model\Product\ProductProjection;

/**
 * Product normalizer interface
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Model
 */
interface ProductNormalizerInterface
{
    /**
     * Normalize product
     * @param ProductProjection $projection
     * @return mixed
     */
    public function normalize(ProductProjection $projection);
}
