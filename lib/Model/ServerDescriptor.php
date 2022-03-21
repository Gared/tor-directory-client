<?php
declare(strict_types=1);

namespace Gared\TorDirectoryClient\Model;

class ServerDescriptor
{
    private string $fingerprint;
    private string $nickname;
    private string $address;
    private int $orPort;
    private int $socksPort;
    private ?int $dirPort;
    private int $uptime;

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    public function getUptime(): int
    {
        return $this->uptime;
    }

    public function setUptime(int $uptime): void
    {
        $this->uptime = $uptime;
    }

    public function getFingerprint(): string
    {
        return $this->fingerprint;
    }

    public function setFingerprint(string $fingerprint): void
    {
        $this->fingerprint = $fingerprint;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getOrPort(): int
    {
        return $this->orPort;
    }

    public function setOrPort(int $orPort): void
    {
        $this->orPort = $orPort;
    }

    public function getSocksPort(): int
    {
        return $this->socksPort;
    }

    public function setSocksPort(int $socksPort): void
    {
        $this->socksPort = $socksPort;
    }

    public function getDirPort(): ?int
    {
        return $this->dirPort;
    }

    public function setDirPort(?int $dirPort): void
    {
        $this->dirPort = $dirPort;
    }
}