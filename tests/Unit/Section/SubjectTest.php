<?php

declare(strict_types=1);

namespace Damianopetrungaro\PHPCommitizen\Section;

use Damianopetrungaro\PHPCommitizen\Configuration;
use Damianopetrungaro\PHPCommitizen\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class SubjectTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider subjectDataProvider
     */
    public function testSubject(
        string $type,
        string $scope,
        string $description,
        string $expected,
        int $minLength,
        int $maxLength
    ): void
    {
        $configurationProphecy = $this->prophesize(Configuration::class);
        $configurationProphecy->maxLengthSubject()->willReturn($maxLength);
        $configurationProphecy->minLengthSubject()->willReturn($minLength);

        $typeProphecy = $this->prophesize(Type::class);
        $typeProphecy->__toString()->willReturn($type);

        $scopeProphecy = $this->prophesize(Scope::class);
        $scopeProphecy->__toString()->willReturn($scope);

        $descriptionProphecy = $this->prophesize(Description::class);
        $descriptionProphecy->__toString()->willReturn($description);

        $subject = Subject::build(
            $typeProphecy->reveal(),
            $scopeProphecy->reveal(),
            $descriptionProphecy->reveal(),
            $configurationProphecy->reveal()
        );

        $this->assertSame($expected, $subject->__toString());
    }

    /**
     * @dataProvider invalidSubjectDataProvider
     */
    public function testSubjectFail(
        string $type,
        string $scope,
        string $description,
        string $errorMessage,
        int $minLength,
        int $maxLength
    ): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($errorMessage);
        $configurationProphecy = $this->prophesize(Configuration::class);
        $configurationProphecy->maxLengthSubject()->willReturn($maxLength);
        $configurationProphecy->minLengthSubject()->willReturn($minLength);

        $typeProphecy = $this->prophesize(Type::class);
        $typeProphecy->__toString()->willReturn($type);

        $scopeProphecy = $this->prophesize(Scope::class);
        $scopeProphecy->__toString()->willReturn($scope);

        $descriptionProphecy = $this->prophesize(Description::class);
        $descriptionProphecy->__toString()->willReturn($description);

        Subject::build(
            $typeProphecy->reveal(),
            $scopeProphecy->reveal(),
            $descriptionProphecy->reveal(),
            $configurationProphecy->reveal()
        );
    }

    public function invalidSubjectDataProvider(): array
    {
        return [
            ['subject', '(scope)', 'description', "Invalid length for subject: 'subject(scope): description'. Must be between 0 and 21", 0, 21],
            ['subject', '', 'description', "Invalid length for subject: 'subject: description'. Must be between 0 and 5", 0, 5],
            ['s', '(s)', 'd', "Invalid length for subject: 's(s): d'. Must be between 0 and 6", 0, 6],
            ['s', '(s)', 'd', "Invalid length for subject: 's(s): d'. Must be between 8 and 10", 8, 10],
        ];
    }

    public function subjectDataProvider(): array
    {
        return [
            ['subject', '(scope)', 'description', 'subject(scope): description', 20, 30],
            ['s', '', 'd', 's: d', 4, 10],
        ];
    }
}
