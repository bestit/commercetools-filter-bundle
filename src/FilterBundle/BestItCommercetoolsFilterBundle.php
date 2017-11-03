<?php

namespace BestIt\Commercetools\FilterBundle;

use BestIt\Commercetools\FilterBundle\DependencyInjection\CompilerPass\TermNormalizerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Filter bundle for filtering and searching products
 *
 * @author  chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle
 * @version $id$
 */
class BestItCommercetoolsFilterBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TermNormalizerPass());
    }
}
