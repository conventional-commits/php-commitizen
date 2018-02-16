<?php

declare(strict_types=1);

namespace Damianopetrungaro\PHPCommitizen\Section;

use Damianopetrungaro\PHPCommitizen\Configuration;
use Damianopetrungaro\PHPCommitizen\Exception\InvalidArgumentException;
use function strlen;

class Subject
{
    /**
     * @var Type
     */
    private $type;

    /**
     * @var Scope|null
     */
    private $scope;

    /**
     * @var Description
     */
    private $description;

    private function __construct(Type $type, ?Scope $scope, Description $description)
    {
        $this->type = $type;
        $this->scope = $scope;
        $this->description = $description;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function build(Type $type, ?Scope $scope, Description $description, Configuration $configuration): self
    {
        // 2 char more needed for the ": " when transforming to string
        $subjectLength = strlen((string)$type) + strlen((string)$scope) + strlen((string)$description) + 2;

        if ($subjectLength > $configuration->maxLengthSubject() || $subjectLength < $configuration->minLengthSubject()) {
            $errorMessage = sprintf("Invalid length for subject: '%s'. Must be between %s and %s",
                "{$type}{$scope}: {$description}",
                $configuration->minLengthSubject(),
                $configuration->maxLengthSubject()
            );
            throw new InvalidArgumentException($errorMessage);
        }

        return new self($type, $scope, $description);
    }

    public function __toString(): string
    {
        return "{$this->type}{$this->scope}: {$this->description}";
    }
}