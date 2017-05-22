<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Form;

use BestIt\Commercetools\FilterBundle\Form\ResetType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Test the reset type
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Form
 * @version    $id$
 */
class ResetTypeTest extends TestCase
{
    /**
     * The form type
     *
     * @var ResetType
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new ResetType();
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
            'baseUrl' => 'foo'
        ];

        $this->fixture->buildView($view, $form, $options);
        static::assertEquals(
            [
                'value' => null,
                'base_url' => 'foo',
                'attr' => []
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
            ->method('setRequired')
            ->with(['baseUrl']);

        $this->fixture->configureOptions($resolver);
    }
}
