<?php

namespace BestIt\Commercetools\FilterBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Transformer for price max value
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle
 * @subpackage Form\Transformer
 */
class PriceMaxDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function reverseTransform($value)
    {
        return $value * 100;
    }

    /**
     * {@inheritDoc}
     */
    public function transform($value)
    {
        return ceil($value / 100);
    }
}
