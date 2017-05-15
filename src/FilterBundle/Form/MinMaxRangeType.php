<?php

namespace BestIt\Commercetools\FilterBundle\Form;

use BestIt\Commercetools\FilterBundle\Form\Transformer\PriceMaxDataTransformer;
use BestIt\Commercetools\FilterBundle\Form\Transformer\PriceMinDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MinMaxRangeType
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Form
 */
class MinMaxRangeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('min');
        $builder->add('max');

        $builder->get('min')->addModelTransformer(new PriceMinDataTransformer());
        $builder->get('max')->addModelTransformer(new PriceMaxDataTransformer());
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // round to get nearest value to show even numbers
        $view->vars = array_replace(
            $view->vars,
            [
                'min' => (new PriceMinDataTransformer())->transform($options['min']),
                'max' => (new PriceMaxDataTransformer())->transform($options['max']),
                'isRange' => $options['isRange']
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'required' => false,
                'isRange' => true
            ]
        );

        $resolver->setRequired(['min', 'max']);
    }
}
