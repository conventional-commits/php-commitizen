<?php

declare(strict_types=1);

namespace Damianopetrungaro\PHPCommitizen;

class Configuration
{
    /**
     * @var int
     */
    private $minLengthType;

    /**
     * @var int
     */
    private $maxLengthType;

    /**
     * @var bool
     */
    private $acceptExtraType;

    /**
     * @var array
     */
    private $types;

    /**
     * @var int
     */
    private $minLengthScope;

    /**
     * @var int
     */
    private $maxLengthScope;

    /**
     * @var bool
     */
    private $acceptExtraScope;

    /**
     * @var array
     */
    private $scopes;

    /**
     * @var int
     */
    private $minLengthDescription;

    /**
     * @var int
     */
    private $maxLengthDescription;

    /**
     * @var int
     */
    private $minLengthSubject;

    /**
     * @var int
     */
    private $maxLengthSubject;

    /**
     * @var int
     */
    private $wrapWidthBody;

    /**
     * @var int
     */
    private $wrapWidthFooter;

    private function __construct(
        int $minLengthType,
        int $maxLengthType,
        bool $acceptExtraType,
        array $types,
        int $minLengthScope,
        int $maxLengthScope,
        bool $acceptExtraScope,
        array $scopes,
        int $minLengthDescription,
        int $maxLengthDescription,
        int $minLengthSubject,
        int $maxLengthSubject,
        int $wrapWidthBody,
        int $wrapWidthFooter
    )
    {
        $this->minLengthType = $minLengthType;
        $this->maxLengthType = $maxLengthType;
        $this->acceptExtraType = $acceptExtraType;
        $this->types = $types;
        $this->minLengthScope = $minLengthScope;
        $this->maxLengthScope = $maxLengthScope;
        $this->acceptExtraScope = $acceptExtraScope;
        $this->scopes = $scopes;
        $this->minLengthDescription = $minLengthDescription;
        $this->maxLengthDescription = $maxLengthDescription;
        $this->minLengthSubject = $minLengthSubject;
        $this->maxLengthSubject = $maxLengthSubject;
        $this->wrapWidthBody = $wrapWidthBody;
        $this->wrapWidthFooter = $wrapWidthFooter;
    }

    public static function fromArray(array $configuration): self
    {
        return new self(
            $configuration['type']['lengthMin'],
            $configuration['type']['lengthMax'],
            $configuration['type']['acceptExtra'],
            $configuration['type']['values'],
            $configuration['scope']['lengthMin'],
            $configuration['scope']['lengthMax'],
            $configuration['scope']['acceptExtra'],
            $configuration['scope']['values'],
            $configuration['description']['lengthMin'],
            $configuration['description']['lengthMax'],
            $configuration['subject']['lengthMin'],
            $configuration['subject']['lengthMax'],
            $configuration['body']['wrap'],
            $configuration['footer']['wrap']
        );
    }

    public function minLengthType(): int
    {
        return $this->minLengthType;
    }

    public function maxLengthType(): int
    {
        return $this->maxLengthType;
    }

    public function acceptExtraType(): bool
    {
        return $this->acceptExtraType;
    }

    public function types(): array
    {
        return $this->types;
    }

    public function minLengthScope(): int
    {
        return $this->minLengthScope;
    }

    public function maxLengthScope(): int
    {
        return $this->maxLengthScope;
    }

    public function acceptExtraScope(): bool
    {
        return $this->acceptExtraScope;
    }

    public function scopes(): array
    {
        return $this->scopes;
    }

    public function minLengthDescription(): int
    {
        return $this->minLengthDescription;
    }

    public function maxLengthDescription(): int
    {
        return $this->maxLengthDescription;
    }

    public function minLengthSubject(): int
    {
        return $this->minLengthSubject;
    }

    public function maxLengthSubject(): int
    {
        return $this->maxLengthSubject;
    }

    public function wrapWidthBody(): int
    {
        return $this->wrapWidthBody;
    }

    public function wrapWidthFooter(): int
    {
        return $this->wrapWidthFooter;
    }
}