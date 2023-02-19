<?php
declare(strict_types=1);

namespace Gared\TorDirectoryClient\Parser;

use Exception;
use Gared\TorDirectoryClient\Model\Flags;
use Gared\TorDirectoryClient\Model\RouterDescriptor;

class RouterStatusParser
{
    /**
     * @return RouterDescriptor[]
     */
    public function parse(string $content): array
    {
        $descriptors = [];
        $lines = explode("\n", $content);
        $lineCount = count($lines);
        for ($i = 0; $i < $lineCount; $i++) {
            if (!str_starts_with($lines[$i], 'r ')) {
                continue;
            }

            $routerDescriptor = new RouterDescriptor();
            $parts = $this->splitLine($lines[$i]);
            $this->parseRLine($parts, $routerDescriptor);

            while (++$i < $lineCount && !str_starts_with($lines[$i], 'r ')) {
                if (str_starts_with($lines[$i], 's ')) {
                    $parts = $this->splitLine($lines[$i]);
                    $this->parseSLine($parts, $routerDescriptor);
                }

                if (str_starts_with($lines[$i], 'pr ')) {
                    $parts = $this->splitLine($lines[$i]);
                    $this->parsePrLine($parts, $routerDescriptor);
                }

                if (str_starts_with($lines[$i], 'w ')) {
                    $parts = $this->splitLine($lines[$i]);
                    $this->parseWLine($parts, $routerDescriptor);
                }

                if (str_starts_with($lines[$i], 'p ')) {
                    $parts = $this->splitLine($lines[$i]);
                    $this->parsePLine($parts, $routerDescriptor);
                }

                if (str_starts_with($lines[$i], 'v ')) {
                    $parts = $this->splitLine($lines[$i]);
                    $this->parseVLine($parts, $routerDescriptor);
                }
            }

            $descriptors[$routerDescriptor->getFingerprint()] = $routerDescriptor;
        }

        return $descriptors;
    }

    private function splitLine(string $line): array
    {
        $line = str_replace("\r", "", $line);
        return preg_split('/[ \t]+/', $line);
    }

    /**
     * @param string[] $parts
     */
    private function parseRLine(array $parts, RouterDescriptor $routerDescriptor): void
    {
//        if (count($parts) !== 9 || count($parts) !== 8) {
//            // error
//        }

        $fingerprint = strtoupper(bin2hex(base64_decode($parts[2] . '=')));

        $routerDescriptor->setNickname($parts[1]);
        $routerDescriptor->setFingerprint($fingerprint);
        $routerDescriptor->setAddress($parts[6]);
        $routerDescriptor->setDirPort((int)$parts[8]);
    }

    private function parseSLine(array $parts, RouterDescriptor $routerDescriptor): void
    {
        unset($parts[0]);
        $routerDescriptor->setFlags(new Flags($parts));
    }

    private function parsePrLine(array $parts, RouterDescriptor $routerDescriptor): void
    {
        unset($parts[0]);
        $parsed = [];

        foreach ($parts as $part) {
            [$key, $value] = explode('=', $part);
            $versions = [];
            $protocolValues = explode(',', $value);

            foreach ($protocolValues as $protocolValue) {
                if (str_contains($protocolValue, '-')) {
                    [$from, $to] = explode('-', $protocolValue);
                    if ($from > $to || $to >= 0x1_0000_0000) {
                        throw new Exception('Error in range: ' . $protocolValue);
                    }

                    for ($j = (int)$from; $j <= $to; $j++) {
                        $versions[] = $j;
                    }
                    continue;
                }

                $versions[] = (int)$protocolValue;
            }

            $parsed[$key] = $versions;
        }

        $routerDescriptor->setProtocols($parsed);

    }

    private function parseWLine(array $parts, RouterDescriptor $routerDescriptor): void
    {
        unset($parts[0]);

        foreach ($parts as $part) {
            [$key, $value] = explode('=', $part);

            $value = trim($value);
            if ($key === 'Bandwidth') {
                $routerDescriptor->setBandwidth($value);
            }

            if ($key === 'Measured') {
                $routerDescriptor->setMeasured($value);
            }

            if ($key === 'Unmeasured') {
                $routerDescriptor->setUnmeasured($value);
            }
        }

    }

    private function parsePLine(array $parts, RouterDescriptor $routerDescriptor): void
    {
        if (count($parts) !== 3) {
            throw new Exception('Invalid line');
        }

        switch ($parts[1]) {
            case 'accept':
            case 'reject':
                $routerDescriptor->setDefaultPolicy($parts[1]);
                $routerDescriptor->setPortList(trim($parts[2]));
        }
    }

    private function parseVLine(array $parts, RouterDescriptor $routerDescriptor): void
    {
        if (strlen($parts[2]) > 2) {
            $routerDescriptor->setVersion($parts[2]);
        }
    }
}