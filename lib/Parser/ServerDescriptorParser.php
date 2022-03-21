<?php
declare(strict_types=1);

namespace Gared\TorDirectoryClient\Parser;

use Gared\TorDirectoryClient\Model\Flags;
use Gared\TorDirectoryClient\Model\RouterDescriptor;
use Gared\TorDirectoryClient\Model\ServerDescriptor;

class ServerDescriptorParser
{
    public function parse(string $content): ServerDescriptor
    {
        $descriptors = [];
        $lines = explode("\n", $content);
        $lineCount = count($lines);
        $serverDescriptor = new ServerDescriptor();

        for ($i = 0; $i < $lineCount; $i++) {
//            if (!str_starts_with($lines[$i], 'r ')) {
//                continue;
//            }



                if (str_starts_with($lines[$i], 'uptime ')) {
                    $parts = $this->splitLine($lines[$i]);
                    $this->parseUptimeLine($parts, $serverDescriptor);
                }

            if (str_starts_with($lines[$i], 'fingerprint ')) {
                $parts = $this->splitLine($lines[$i]);
                $this->parseFingerprintLine($parts, $serverDescriptor);
            }

            if (str_starts_with($lines[$i], 'router ')) {
                $parts = $this->splitLine($lines[$i]);
                $this->parseRouterLine($parts, $serverDescriptor);
            }

//                if (str_starts_with($lines[$i], 'pr ')) {
//                    $parts = $this->splitLine($lines[$i]);
//                    $this->parsePrLine($parts, $serverDescriptor);
//                }
//
//                if (str_starts_with($lines[$i], 'w ')) {
//                    $parts = $this->splitLine($lines[$i]);
//                    $this->parseWLine($parts, $serverDescriptor);
//                }
            }

//            $descriptors[$serverDescriptor->getFingerprint()] = $serverDescriptor;
//        }

        return $serverDescriptor;
    }

    private function splitLine(string $line): array
    {
        return preg_split('/[ \t]+/', $line);
    }

    /**
     * @param string[] $parts
     */
    private function parseFingerprintLine(array $parts, ServerDescriptor $serverDescriptor): void
    {
        unset($parts[0]);
        $fingerprint = implode('', $parts);
//        $fingerprint = strtoupper(bin2hex(base64_decode($parts[2] . '=')));

        $serverDescriptor->setFingerprint($fingerprint);
    }

    private function parseUptimeLine(array $parts, ServerDescriptor $serverDescriptor): void
    {
        unset($parts[0]);
        $serverDescriptor->setUptime((int)$parts[1]);
    }

    private function parseRouterLine(array $parts, ServerDescriptor $serverDescriptor): void
    {
        $serverDescriptor->setNickname($parts[1]);
        $serverDescriptor->setAddress($parts[2]);
        $serverDescriptor->setOrPort((int)$parts[3]);
        $serverDescriptor->setSocksPort((int)$parts[4]);
        $serverDescriptor->setDirPort(array_key_exists(5, $parts) ? (int)$parts[5] : null);

    }

    private function parsePLine(array $parts, RouterDescriptor $routerDescriptor): void
    {
        if (count($parts) !== 3) {
            throw new \Exception('blub');
        }

        switch ($parts[1]) {
            case 'accept':
            case 'reject':
        }

    }

}