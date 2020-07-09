<?php

declare(strict_types=1);

namespace Damianopetrungaro\PHPCommitizen\Section;

use Damianopetrungaro\PHPCommitizen\Configuration;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class FooterTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider footerDataProvider
     */
    public function testFooter(string $actual, string $expected): void
    {
        $configurationProphecy = $this->prophesize(Configuration::class);
        $configurationProphecy->wrapWidthFooter()->willReturn(72);

        $footer = Footer::build($actual, $configurationProphecy->reveal());
        $this->assertSame($expected, $footer->__toString());
    }

    public function footerDataProvider(): array
    {
        return [
            ['footer', 'footer'],
            ['  trim me   ', 'trim me'],
            [
                '3FiHRUhcIzAOmvReAY4senqnGqzff7gDZSsStJMhd0TBxcrmNmZqhlQFAfOrsryWvlnRydMMqXgbzSuY5OqrDOyUjz',
                "3FiHRUhcIzAOmvReAY4senqnGqzff7gDZSsStJMhd0TBxcrmNmZqhlQFAfOrsryWvlnRydMM\nqXgbzSuY5OqrDOyUjz",
            ], [
                "+é§°#ò;,:._-|\\!\"£$%&/()=?^1234567890ì<>+é§°#ò;,:._-|\\!\"£$%&/()=?^1234567890ì<>",
                "+é§°#ò;,:._-|\\!\"£$%&/()=?^1234567890ì<>+é§°#ò;,:._-|\\!\"£$%&/(\n)=?^1234567890ì<>",
            ], [
                "3FiHRUhcIzAOm\nvReAY4senqnGqzff7gDZSsStJMhd0TBxcrmNmZqhlQFAfOrsryWvlnRydMMqXgbzSuY5OqrDOyUjz3FiHRUhc",
                "3FiHRUhcIzAOm\nvReAY4senqnGqzff7gDZSsStJMhd0TBxcrmNmZqhlQFAfOrsryWvlnRydMMqXgbzSuY5OqrD\nOyUjz3FiHRUhc",
            ],
        ];
    }
}
