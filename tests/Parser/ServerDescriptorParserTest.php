<?php
declare(strict_types=1);

namespace Gared\TorDirectoryClientTest\Parser;

use Gared\TorDirectoryClient\Parser\ServerDescriptorParser;
use PHPUnit\Framework\TestCase;

class ServerDescriptorParserTest extends TestCase
{
    public function parseDataProvider(): array
    {
        return [
            [
                __DIR__ . '/../Fixtures/router.txt'
            ]
        ];
    }

    /**
     * @dataProvider parseDataProvider
     */
    public function testParse(string $fixturePath): void
    {
        $content = file_get_contents($fixturePath);

        $parser = new ServerDescriptorParser();
        $serverDescriptor = $parser->parse($content);

        self::assertSame(64807, $serverDescriptor->getUptime());
        self::assertSame('0C9B3C686421C5A8C20BAA0D7369CD929F43A174', $serverDescriptor->getFingerprint());
        self::assertSame('nsaPwned', $serverDescriptor->getNickname());
    }
}