<?php

declare(strict_types=1);

namespace Damianopetrungaro\PHPCommitizen;

use Damianopetrungaro\PHPCommitizen\Section\Body;
use Damianopetrungaro\PHPCommitizen\Section\Footer;
use Damianopetrungaro\PHPCommitizen\Section\Subject;
use function exec;
use function var_dump;

class CreateConventionalCommit
{
    public function __invoke(Subject $subject, Body $body, Footer $footer, bool $addAllToStage): void
    {
        if ($addAllToStage) {
            $this->exec('git add .');
        }

        $safeSubject = escapeshellarg((string)$subject);
        $safeBody = escapeshellarg((string)$body);
        $safeFooter = escapeshellarg((string)$footer);
        $this->exec("git commit -m $safeSubject -m $safeBody -m $safeFooter");
    }

    protected function exec(string $command): void
    {
        exec($command);
    }
}