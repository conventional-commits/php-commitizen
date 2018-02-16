<?php

declare(strict_types=1);

namespace Damianopetrungaro\PHPCommitizen\Section;

use Damianopetrungaro\PHPCommitizen\Configuration;
use Damianopetrungaro\PHPCommitizen\Exception\InvalidArgumentException;
use function implode;
use function in_array;
use function sprintf;
use function strlen;

class Type
{
    /**
     * @var string
     */
    private $type;

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function build(string $type, Configuration $configuration): self
    {
        $type = trim($type);
        $typeLength = strlen($type);

        if ($typeLength > $configuration->maxLengthType() || $typeLength < $configuration->minLengthType()) {
            $errorMessage = sprintf("Invalid length for type: '%s'. Must be between %s and %s",
                $type,
                $configuration->minLengthType(),
                $configuration->maxLengthType()
            );
            throw new InvalidArgumentException($errorMessage);
        }

        if (!$configuration->acceptExtraType() && !in_array($type, $configuration->types(), true)) {
            $validTypes = implode(', ', $configuration->types());
            throw new InvalidArgumentException("Invalid type: '$type'. Valid types are: [$validTypes]");
        }

        return new self($type);
    }

    public function __toString(): string
    {
        return $this->type;
    }
}