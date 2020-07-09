<?php

declare(strict_types=1);

namespace Damianopetrungaro\PHPCommitizen;

use Damianopetrungaro\PHPCommitizen\Exception\InvalidArgumentException;
use Damianopetrungaro\PHPCommitizen\Section\Body;
use Damianopetrungaro\PHPCommitizen\Section\Footer;
use Damianopetrungaro\PHPCommitizen\Section\Subject;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

final class CommitCommandTest extends TestCase
{
    use ProphecyTrait;

    public function testCustomConfigurationFileNotFound(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Custom configuration file does not exists: 'not_existing_file.php'");
        $configuration = require __DIR__ . '/../../.php-commitizen.php';
        $app = new Application('PHP Commitizen');
        $app->add(new CommitCommand($configuration, new CreateConventionalCommit()));

        $command = $app->find('commit');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'config' => 'not_existing_file.php'
        ]);
    }

    public function testCustomConfigurationFileDoesNotReturnAnArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Custom configuration file must return an array');
        $configuration = require __DIR__ . '/../../.php-commitizen.php';
        $app = new Application('PHP Commitizen');
        $app->add(new CommitCommand($configuration, new CreateConventionalCommit()));
        $command = $app->find('commit');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'config' => __DIR__ . '/../../tests/Unit/Fixture/configuration.php',
        ]);
    }

    public function testCustomConfigurationIsMerged(): void
    {
        $that = $this;
        $createConventionalCommitProphecy = $this->prophesize(CreateConventionalCommit::class);
        $createConventionalCommitProphecy
            ->__invoke(
                Argument::type(Subject::class),
                Argument::type(Body::class),
                Argument::type(Footer::class),
                false
            )
            ->shouldBeCalledTimes(1)
            ->will(function (array $data) use ($that) {
                [$subject, $body, $footer, $addAllToStage] = $data;
                $that->assertSame('fix(scope): commit description', (string)$subject);
                $that->assertSame('commit body', (string)$body);
                $that->assertSame('commit footer', (string)$footer);
                $that->assertFalse($addAllToStage);
            });

        $commitCommand = new CommitCommand([], $createConventionalCommitProphecy->reveal());
        $app = new Application('PHP Commitizen');
        $app->add($commitCommand);

        $command = $app->find('commit');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs([
            'fix',
            'scope',
            'commit description',
            'commit body',
            'commit footer',
        ]);
        $commandTester->execute([
            'command' => $command->getName(),
            'config' => '.php-commitizen.php'
        ]);
    }
}
