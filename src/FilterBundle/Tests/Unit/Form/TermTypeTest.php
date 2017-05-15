<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Form;

use BestIt\Commercetools\FilterBundle\Form\MinMaxRangeType;
use BestIt\Commercetools\FilterBundle\Form\TermType;
use BestIt\Commercetools\FilterBundle\Form\Transformer\PriceMaxDataTransformer;
use BestIt\Commercetools\FilterBundle\Form\Transformer\PriceMinDataTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Test the term type
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Form
 * @version    $id$
 */
class TermTypeTest extends TestCase
{
    /**
     * The term type
     *
     * @var TermType
     */
    private $fixture;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new TermType();
    }

    /**
     * Test correct inheritor
     *
     * @return void
     */
    public function testInheritor()
    {
        static::assertInstanceOf(ChoiceType::class, $this->fixture);
    }
}
