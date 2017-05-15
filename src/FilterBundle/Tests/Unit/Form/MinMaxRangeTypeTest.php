<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Form;

use BestIt\Commercetools\FilterBundle\Form\MinMaxRangeType;
use BestIt\Commercetools\FilterBundle\Form\Transformer\PriceMaxDataTransformer;
use BestIt\Commercetools\FilterBundle\Form\Transformer\PriceMinDataTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Test the min max range form type
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Form
 * @version    $id$
 */
class MinMaxRangeTypeTest extends TestCase
{
    /**
     * The min max type
     *
     * @var MinMaxRangeType
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new MinMaxRangeType();
    }

    /**
     * Test build form
     *
     * @return void
     */
    public function testBuildForm()
    {
        $builder = $this->createMock(FormBuilderInterface::class);
        $builder
            ->expects(self::exactly(2))
            ->method('add')
            ->withConsecutive(['min'], ['max']);

        $builder
            ->expects(self::exactly(2))
            ->method('addModelTransformer')
            ->withConsecutive(
                [self::isInstanceOf(PriceMinDataTransformer::class)],
                [self::isInstanceOf(PriceMaxDataTransformer::class)]
            );

        $builder
            ->expects(self::exactly(2))
            ->method('get')
            ->withConsecutive(['min'], ['max'])
            ->willReturnSelf();

        $this->fixture->buildForm($builder, []);
    }

    /**
     * Test build view
     *
     * @return void
     */
    public function testBuildView()
    {
        $view = new FormView();
        $form = $this->createMock(FormInterface::class);
        $options = [
            'min' => 4500,
            'max' => 7800,
            'isRange' => true
        ];

        $this->fixture->buildView($view, $form, $options);
        static::assertEquals(
            [
                'value' => null,
                'attr' => [],
                'min' => 45.0,
                'max' => 78.0,
                'isRange' => true
            ],
            $view->vars
        );
    }

    /**
     * Test configure options
     *
     * @return void
     */
    public function testConfigureOptions()
    {
        $resolver = $this->createMock(OptionsResolver::class);
        $resolver
            ->expects(self::once())
            ->method('setDefaults')
            ->with(
                self::equalTo(
                    [
                        'required' => false,
                        'isRange' => true
                    ]
                )
            );

        $resolver
            ->expects(self::once())
            ->method('setRequired')
            ->with(self::equalTo(['min', 'max']));

        $this->fixture->configureOptions($resolver);
    }
}
