<?php

declare(strict_types=1);

namespace Damianopetrungaro\PHPCommitizen\Section;

use Damianopetrungaro\PHPCommitizen\Configuration;
use function wordwrap;

class Footer
{
    /**
     * @var string
     */
    private $footer;

    private function __construct(string $footer)
    {
        $this->footer = $footer;
    }

    public static function build(string $footer, Configuration $configuration): self
    {
        $footer = trim($footer);
        $footer = wordwrap($footer, $configuration->wrapWidthFooter(), PHP_EOL, true);

        return new self($footer);
    }

    public function __toString(): string
    {
        return $this->footer;
    }
}