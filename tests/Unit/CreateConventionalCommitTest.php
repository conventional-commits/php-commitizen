<?php

declare(strict_types=1);

namespace Damianopetrungaro\PHPCommitizen;

use Damianopetrungaro\PHPCommitizen\Section\Body;
use Damianopetrungaro\PHPCommitizen\Section\Footer;
use Damianopetrungaro\PHPCommitizen\Section\Subject;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class CreateConventionalCommitTest extends TestCase
{
    use ProphecyTrait;

    public function testCreateConventionalCommit(): void
    {
        $createConventionalCommit = $this->createPartialMock(CreateConventionalCommit::class, ['exec']);
        $createConventionalCommit
            ->expects($this->once())
            ->method('exec')
            ->with($this->equalTo("git commit -m 'subject' -m 'body' -m 'footer'"));

        $subjectProphecy = $this->prophesize(Subject::class);
        $subjectProphecy->__toString()->shouldBeCalled()->willReturn('subject');

        $bodyProphecy = $this->prophesize(Body::class);
        $bodyProphecy->__toString()->shouldBeCalled()->willReturn('body');

        $footerProphecy = $this->prophesize(Footer::class);
        $footerProphecy->__toString()->shouldBeCalled()->willReturn('footer');

        $createConventionalCommit($subjectProphecy->reveal(), $bodyProphecy->reveal(), $footerProphecy->reveal(), false);
    }

    public function testCreateConventionalCommitWithAddAllFlag(): void
    {
        $createConventionalCommit = $this->createPartialMock(CreateConventionalCommit::class, ['exec']);
        $createConventionalCommit
            ->expects($this->exactly(2))
            ->method('exec')
            ->withConsecutive(
                [$this->equalTo('git add .')],
                [$this->equalTo("git commit -m 'subject' -m 'body' -m 'footer'")]
            );

        $subjectProphecy = $this->prophesize(Subject::class);
        $subjectProphecy->__toString()->shouldBeCalled()->willReturn('subject');

        $bodyProphecy = $this->prophesize(Body::class);
        $bodyProphecy->__toString()->shouldBeCalled()->willReturn('body');

        $footerProphecy = $this->prophesize(Footer::class);
        $footerProphecy->__toString()->shouldBeCalled()->willReturn('footer');

        $createConventionalCommit($subjectProphecy->reveal(), $bodyProphecy->reveal(), $footerProphecy->reveal(), true);
    }
}
