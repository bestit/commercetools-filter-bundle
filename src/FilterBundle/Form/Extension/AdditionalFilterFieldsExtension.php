<?php

namespace BestIt\Commercetools\FilterBundle\Form\Extension;

use BestIt\Commercetools\FilterBundle\Form\FilterType;
use BestIt\Commercetools\FilterBundle\Model\Search\SearchContext;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Extension for adding additional fields
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Form\Extension
 */
class AdditionalFilterFieldsExtension extends AbstractTypeExtension
{
    /**
     * The bundle config
     *
     * @var array
     */
    private $config;

    /**
     * AdditionalFilterFieldsExtension constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType(): string
    {
        return FilterType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var SearchContext $context */
        $context = $options['context'];

        foreach ($context->getFilterFormFields() as $name => $value) {
            $builder->add($name, HiddenType::class, [
                'empty_data' => $value
            ]);
        }
    }
}
