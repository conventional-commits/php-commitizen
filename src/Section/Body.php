<?php

declare(strict_types=1);

namespace Damianopetrungaro\PHPCommitizen\Section;

use Damianopetrungaro\PHPCommitizen\Configuration;
use function wordwrap;

class Body
{
    /**
     * @var string
     */
    private $body;

    private function __construct(string $body)
    {
        $this->body = $body;
    }

    public static function build(string $body, Configuration $configuration): self
    {
        $body = trim($body);
        $body = wordwrap($body, $configuration->wrapWidthBody(), PHP_EOL, true);

        return new self($body);
    }

    public function __toString(): string
    {
        return $this->body;
    }
}