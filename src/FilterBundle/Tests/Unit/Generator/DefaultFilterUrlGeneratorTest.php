<?php

namespace BestIt\Commercetools\FilterBundle\Tests\Unit\Generator;

use BestIt\Commercetools\FilterBundle\Generator\DefaultFilterUrlGenerator;
use Commercetools\Core\Model\Category\Category;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Test default url generator
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @category   Tests\Unit
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Generator
 * @version    $id$
 */
class DefaultFilterUrlGeneratorTest extends TestCase
{
    /**
     * The generator
     *
     * @var DefaultFilterUrlGenerator
     */
    private $fixture;

    /**
     * The url generator
     *
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new DefaultFilterUrlGenerator(
            $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class)
        );
    }

    /**
     * Test generate by category
     */
    public function testGenerateByCategory()
    {
        $request = new Request(['p' => 4, 'v' => 'list']);
        $category = new Category();

        $this->urlGenerator
            ->expects(static::once())
            ->method('generate')
            ->with($category)
            ->willReturn($result = 'http://foobar');

        static::assertSame($result, $this->fixture->generateByCategory($request, $category));
    }

    /**
     * Test generate by category
     */
    public function testGenerateBySearch()
    {
        $request = new Request(['p' => 4, 'v' => 'list']);
        $search = 'foobar';

        $this->urlGenerator
            ->expects(static::once())
            ->method('generate')
            ->with('search_index', ['search' => $search])
            ->willReturn($result = 'http://foobar');

        static::assertSame($result, $this->fixture->generateBySearch($request, $search));
    }
}
