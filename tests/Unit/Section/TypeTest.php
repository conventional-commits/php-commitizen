<?php

declare(strict_types=1);

namespace Damianopetrungaro\PHPCommitizen\Section;

use Damianopetrungaro\PHPCommitizen\Configuration;
use Damianopetrungaro\PHPCommitizen\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class TypeTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider typeDataProvider
     */
    public function testType(
        string $actual,
        string $expected,
        int $minLength,
        int $maxLength,
        bool $acceptExtraType,
        array $allowedTypes
    ): void
    {
        $configurationProphecy = $this->prophesize(Configuration::class);
        $configurationProphecy->maxLengthType()->willReturn($maxLength);
        $configurationProphecy->minLengthType()->willReturn($minLength);
        $configurationProphecy->acceptExtraType()->willReturn($acceptExtraType);
        $configurationProphecy->types()->willReturn($allowedTypes);

        $type = Type::build($actual, $configurationProphecy->reveal());
        $this->assertSame($expected, $type->__toString());
    }

    /**
     * @dataProvider invalidTypeDataProvider
     */
    public function testTypeFail(
        string $actual,
        string $errorMessage,
        int $minLength,
        int $maxLength,
        bool $acceptExtraType,
        array $allowedTypes
    ): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($errorMessage);
        $configurationProphecy = $this->prophesize(Configuration::class);
        $configurationProphecy->maxLengthType()->willReturn($maxLength);
        $configurationProphecy->minLengthType()->willReturn($minLength);
        $configurationProphecy->acceptExtraType()->willReturn($acceptExtraType);
        $configurationProphecy->types()->willReturn($allowedTypes);

        Type::build($actual, $configurationProphecy->reveal());
    }

    public function invalidTypeDataProvider(): array
    {
        return [
            ['not in list', "Invalid type: 'not in list'. Valid types are: [a, b]", 0, 100, false, ['a', 'b']],
            ['   too long   ', "Invalid length for type: 'too long'. Must be between 0 and 1", 0, 1, false, []],
            ['too short ', "Invalid length for type: 'too short'. Must be between 35 and 100", 35, 100, false, []],
        ];
    }

    public function typeDataProvider(): array
    {
        return [
            ['not in list     ', 'not in list', 0, 12, true, ['a', 'b']],
            ['a', 'a', 1, 12, false, ['a', 'b']],
        ];
    }
}
