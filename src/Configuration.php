<?php

declare(strict_types=1);

namespace Damianopetrungaro\PHPCommitizen;

class Configuration
{
    private $params;

    private function __construct(array $params)
    {
        $this->params = $params;
    }

    public static function fromArray(array $conf): self
    {
        return new self([
            'minLengthType'        => $conf['type']['lengthMin'],
            'maxLengthType'        => $conf['type']['lengthMax'],
            'acceptExtraType'      => $conf['type']['acceptExtra'],
            'types'                => $conf['type']['values'],
            'minLengthScope'       => $conf['scope']['lengthMin'],
            'maxLengthScope'       => $conf['scope']['lengthMax'],
            'acceptExtraScope'     => $conf['scope']['acceptExtra'],
            'scopes'               => $conf['scope']['values'],
            'minLengthDescription' => $conf['description']['lengthMin'],
            'maxLengthDescription' => $conf['description']['lengthMax'],
            'minLengthSubject'     => $conf['subject']['lengthMin'],
            'maxLengthSubject'     => $conf['subject']['lengthMax'],
            'wrapWidthBody'        => $conf['body']['wrap'],
            'wrapWidthFooter'      => $conf['footer']['wrap'],
        ]);
    }

    public function minLengthType(): int
    {
        return $this->params['minLengthType'];
    }

    public function maxLengthType(): int
    {
        return $this->params['maxLengthType'];
    }

    public function acceptExtraType(): bool
    {
        return $this->params['acceptExtraType'];
    }

    public function types(): array
    {
        return $this->params['types'];
    }

    public function minLengthScope(): int
    {
        return $this->params['minLengthScope'];
    }

    public function maxLengthScope(): int
    {
        return $this->params['maxLengthScope'];
    }

    public function acceptExtraScope(): bool
    {
        return $this->params['acceptExtraScope'];
    }

    public function scopes(): array
    {
        return $this->params['scopes'];
    }

    public function minLengthDescription(): int
    {
        return $this->params['minLengthDescription'];
    }

    public function maxLengthDescription(): int
    {
        return $this->params['maxLengthDescription'];
    }

    public function minLengthSubject(): int
    {
        return $this->params['minLengthSubject'];
    }

    public function maxLengthSubject(): int
    {
        return $this->params['maxLengthSubject'];
    }

    public function wrapWidthBody(): int
    {
        return $this->params['wrapWidthBody'];
    }

    public function wrapWidthFooter(): int
    {
        return $this->params['wrapWidthFooter'];
    }
}
