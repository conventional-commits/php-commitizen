<?php

declare(strict_types=1);

namespace Damianopetrungaro\PHPCommitizen\Section;

use Damianopetrungaro\PHPCommitizen\Configuration;
use Damianopetrungaro\PHPCommitizen\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class ScopeTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider scopeDataProvider
     */
    public function testScope(
        string $actual,
        string $expected,
        int $minLength,
        int $maxLength,
        bool $acceptExtraScope,
        array $allowedScopes
    ): void
    {
        $configurationProphecy = $this->prophesize(Configuration::class);
        $configurationProphecy->maxLengthScope()->willReturn($maxLength);
        $configurationProphecy->minLengthScope()->willReturn($minLength);
        $configurationProphecy->acceptExtraScope()->willReturn($acceptExtraScope);
        $configurationProphecy->scopes()->willReturn($allowedScopes);

        $scope = Scope::build($actual, $configurationProphecy->reveal());
        $this->assertSame($expected, $scope->__toString());
    }

    /**
     * @dataProvider invalidScopeDataProvider
     */
    public function testScopeFail(
        string $actual,
        string $errorMessage,
        int $minLength,
        int $maxLength,
        bool $acceptExtraScope,
        array $allowedScopes
    ): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($errorMessage);
        $configurationProphecy = $this->prophesize(Configuration::class);
        $configurationProphecy->maxLengthScope()->willReturn($maxLength);
        $configurationProphecy->minLengthScope()->willReturn($minLength);
        $configurationProphecy->acceptExtraScope()->willReturn($acceptExtraScope);
        $configurationProphecy->scopes()->willReturn($allowedScopes);

        Scope::build($actual, $configurationProphecy->reveal());
    }

    public function invalidScopeDataProvider(): array
    {
        return [
            ['not in list', "Invalid scope: 'not in list'. Valid scopes are: [a, b]", 0, 100, false, ['a', 'b']],
            ['   too long   ', "Invalid length for scope: 'too long'. Must be between 0 and 1", 0, 1, false, []],
            ['too short ', "Invalid length for scope: 'too short'. Must be between 35 and 100", 35, 100, false, []],
        ];
    }

    public function scopeDataProvider(): array
    {
        return [
            ['not in list     ', '(not in list)', 0, 12, true, ['a', 'b']],
            ['a', '(a)', 1, 12, false, ['a', 'b']],
        ];
    }
}
