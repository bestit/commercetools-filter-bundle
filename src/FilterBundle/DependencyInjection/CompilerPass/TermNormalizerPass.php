<?php

namespace BestIt\Commercetools\FilterBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class TermNormalizerPass
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\Commercetools\FilterBundle\DependencyInjection\CompilerPass
 */
class TermNormalizerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $collectionDefinition = $container->getDefinition('best_it_commercetools_filter.model_normalizer.term_normalizer_collection');

        $storage = [];
        foreach ($container->findTaggedServiceIds('best_it_commercetools_filter.term_normalizer') as $id => $tags) {
            foreach ($tags as $attributes) {
                $storage[] = [
                    'service' => new Reference($id),
                    'priority' => $attributes['priority'] ?? 0
                ];
            }
        }

        // Sorting services
        usort($storage, function ($first, $second) {
            return $second['priority'] <=> $first['priority'];
        });

        // At least ... add ordered list
        foreach ($storage as $item) {
            $collectionDefinition->addMethodCall('add', [$item['service']]);
        }
    }
}
