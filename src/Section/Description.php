<?php

declare(strict_types=1);

namespace Damianopetrungaro\PHPCommitizen\Section;

use Damianopetrungaro\PHPCommitizen\Configuration;
use Damianopetrungaro\PHPCommitizen\Exception\InvalidArgumentException;
use function strlen;

class Description
{
    /**
     * @var string
     */
    private $description;

    private function __construct(string $description)
    {
        $this->description = $description;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function build(string $description, Configuration $configuration): self
    {
        $description = trim($description);
        $descriptionLength = strlen($description);

        if ($descriptionLength > $configuration->maxLengthDescription() ||
            $descriptionLength < $configuration->minLengthDescription()
        ) {
            $errorMessage = sprintf("Invalid length for description: '%s'. Must be between %s and %s",
                $description,
                $configuration->minLengthDescription(),
                $configuration->maxLengthDescription()
            );
            throw new InvalidArgumentException($errorMessage);
        }

        return new self($description);
    }

    public function __toString(): string
    {
        return $this->description;
    }
}