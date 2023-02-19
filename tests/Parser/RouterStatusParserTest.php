<?php
declare(strict_types=1);

namespace Gared\TorDirectoryClientTest\Parser;

use Gared\TorDirectoryClient\Parser\RouterStatusParser;
use PHPUnit\Framework\TestCase;

class RouterStatusParserTest extends TestCase
{
    public function parseDataProvider(): array
    {
        return [
            [
                __DIR__ . '/../Fixtures/consensus.txt'
            ]
        ];
    }

    /**
     * @dataProvider parseDataProvider
     */
    public function testParse(string $fixturePath): void
    {
        $content = file_get_contents($fixturePath);

        $parser = new RouterStatusParser();
        $routerDescriptors = $parser->parse($content);

        self::assertCount(6894, $routerDescriptors);

        $routerDescriptor = $routerDescriptors[array_key_first($routerDescriptors)];
        $flags = $routerDescriptor->getFlags();

        self::assertSame('seele', $routerDescriptor->getNickname());
        self::assertSame('104.53.221.159', $routerDescriptor->getAddress());
        self::assertSame('000A10D43011EA4928A35F610405F92B4433B4DC', $routerDescriptor->getFingerprint());
        self::assertSame(true, $flags->isFast());
        self::assertSame(true, $flags->isHsDir());
        self::assertSame('reject', $routerDescriptor->getDefaultPolicy());
        self::assertSame('1-65535', $routerDescriptor->getPortList());
        self::assertSame(['Cons', 'Desc', 'DirCache', 'FlowCtrl', 'HSDir', 'HSIntro', 'HSRend', 'Link', 'LinkAuth', 'Microdesc', 'Padding', 'Relay'], array_keys($routerDescriptor->getProtocols()));
        self::assertSame('500', $routerDescriptor->getBandwidth());
        self::assertSame('0.4.6.9', $routerDescriptor->getVersion());
    }
}