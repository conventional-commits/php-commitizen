<?php

declare(strict_types=1);

namespace Damianopetrungaro\PHPCommitizen\Section;

use Damianopetrungaro\PHPCommitizen\Configuration;
use Damianopetrungaro\PHPCommitizen\Exception\InvalidArgumentException;
use function implode;
use function in_array;
use function strlen;

class Scope
{
    /**
     * @var string
     */
    private $scope;

    private function __construct(string $scope)
    {
        $this->scope = $scope;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function build(string $scope, Configuration $configuration): self
    {
        $scope = trim($scope);
        $scopeLength = strlen($scope);

        if ($scopeLength > $configuration->maxLengthScope() || $scopeLength < $configuration->minLengthScope()) {
            $errorMessage = sprintf("Invalid length for scope: '%s'. Must be between %s and %s",
                $scope,
                $configuration->minLengthScope(),
                $configuration->maxLengthScope()
            );
            throw new InvalidArgumentException($errorMessage);
        }

        if (!$configuration->acceptExtraScope() && !in_array($scope, $configuration->scopes(), true)) {
            $validScopes = implode(', ', $configuration->scopes());
            throw new InvalidArgumentException("Invalid scope: '$scope'. Valid scopes are: [$validScopes]");
        }


        return new self($scope);
    }

    public function __toString(): string
    {
        return "($this->scope)";
    }
}