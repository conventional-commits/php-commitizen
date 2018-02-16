<?php

declare(strict_types=1);

namespace Damianopetrungaro\PHPCommitizen;

use PHPUnit\Framework\TestCase;

final class ConfigurationTest extends TestCase
{
    /**
     * @dataProvider configurationDataProvider
     */
    public function testConfigurationFromArray(array $config): void
    {
        $configuration = Configuration::fromArray($config);
        $this->assertSame($configuration->minLengthType(), $config['type']['lengthMin']);
        $this->assertSame($configuration->maxLengthType(), $config['type']['lengthMax']);
        $this->assertSame($configuration->acceptExtraType(), $config['type']['acceptExtra']);
        $this->assertSame($configuration->types(), $config['type']['values']);
        $this->assertSame($configuration->minLengthScope(), $config['scope']['lengthMin']);
        $this->assertSame($configuration->maxLengthScope(), $config['scope']['lengthMax']);
        $this->assertSame($configuration->acceptExtraScope(), $config['scope']['acceptExtra']);
        $this->assertSame($configuration->scopes(), $config['scope']['values']);
        $this->assertSame($configuration->minLengthDescription(), $config['description']['lengthMin']);
        $this->assertSame($configuration->maxLengthDescription(), $config['description']['lengthMax']);
        $this->assertSame($configuration->minLengthSubject(), $config['subject']['lengthMin']);
        $this->assertSame($configuration->maxLengthSubject(), $config['subject']['lengthMax']);
        $this->assertSame($configuration->wrapWidthBody(), $config['body']['wrap']);
        $this->assertSame($configuration->wrapWidthFooter(), $config['footer']['wrap']);
    }

    public function configurationDataProvider(): array
    {
        return [[[
            'type' => [
                'lengthMin' => 1,
                'lengthMax' => 5,
                'acceptExtra' => false,
                'values' => ['feat', 'fix'],
            ],
            'scope' => [
                'lengthMin' => 0,
                'lengthMax' => 10,
                'acceptExtra' => true,
                'values' => [],
            ],
            'description' => [
                'lengthMin' => 1,
                'lengthMax' => 44,
            ],
            'subject' => [
                'lengthMin' => 1,
                'lengthMax' => 50,
            ],
            'body' => [
                'wrap' => 72,
            ],
            'footer' => [
                'wrap' => 72,
            ],
        ]]];
    }
}