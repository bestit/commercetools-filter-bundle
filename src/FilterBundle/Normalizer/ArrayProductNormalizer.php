<?php

namespace BestIt\Commercetools\FilterBundle\Normalizer;

use Commercetools\Core\Model\Product\ProductProjection;

/**
 * Array product normalizer
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model
 */
class ArrayProductNormalizer implements ProductNormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize(ProductProjection $projection)
    {
        return $projection->toArray();
    }
}
