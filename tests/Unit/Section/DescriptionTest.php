<?php

declare(strict_types=1);

namespace Damianopetrungaro\PHPCommitizen\Section;

use Damianopetrungaro\PHPCommitizen\Configuration;
use Damianopetrungaro\PHPCommitizen\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class DescriptionTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider descriptionDataProvider
     */
    public function testDescription(string $actual, string $expected, int $minLength, int $maxLength): void
    {
        $configurationProphecy = $this->prophesize(Configuration::class);
        $configurationProphecy->maxLengthDescription()->willReturn($maxLength);
        $configurationProphecy->minLengthDescription()->willReturn($minLength);

        $description = Description::build($actual, $configurationProphecy->reveal());
        $this->assertSame($expected, $description->__toString());
    }

    /**
     * @dataProvider invalidDescriptionDataProvider
     */
    public function testDescriptionFail(string $actual, string $expected, int $minLength, int $maxLength): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid length for description: '$expected'. Must be between $minLength and $maxLength");
        $configurationProphecy = $this->prophesize(Configuration::class);
        $configurationProphecy->maxLengthDescription()->willReturn($maxLength);
        $configurationProphecy->minLengthDescription()->willReturn($minLength);

        Description::build($actual, $configurationProphecy->reveal());
    }

    public function invalidDescriptionDataProvider(): array
    {
        return [
            ['description', 'description', 1, 4],
            ['  trim me   ', 'trim me', 0, 3],
            ['', '', 1, 100],
        ];
    }

    public function descriptionDataProvider(): array
    {
        return [
            ['description', 'description', 1, 15],
            ['   trim me   ', 'trim me', 0, 7],
            ['|!"£$%&/()=?^Pé*ç°§;:_', '|!"£$%&/()=?^Pé*ç°§;:_', 0, 1000],
            ['', '', 0, 0],
        ];
    }
}
