<?php
declare(strict_types=1);

namespace Gared\TorDirectoryClient\Model;

class RouterDescriptor
{
    private string $nickname;
    private string $fingerprint;
    private string $address;
    private Flags $flags;
    private array $protocols;
    private string $bandwidth;
    private ?string $measured = null;
    private ?string $unmeasured = null;
    private int $dirPort;
    private string $defaultPolicy;
    private string $portList;
    private string $version;

    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    public function setFingerprint(string $fingerprint): void
    {
        $this->fingerprint = $fingerprint;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function setFlags(Flags $flags): void
    {
        $this->flags = $flags;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getFingerprint(): string
    {
        return $this->fingerprint;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getFlags(): Flags
    {
        return $this->flags;
    }

    public function getProtocols(): array
    {
        return $this->protocols;
    }

    public function setProtocols(array $protocols): void
    {
        $this->protocols = $protocols;
    }

    public function getBandwidth(): string
    {
        return $this->bandwidth;
    }

    public function setBandwidth(string $bandwidth): void
    {
        $this->bandwidth = $bandwidth;
    }

    public function getMeasured(): ?string
    {
        return $this->measured;
    }

    public function setMeasured(string $measured): void
    {
        $this->measured = $measured;
    }

    public function getUnmeasured(): ?string
    {
        return $this->unmeasured;
    }

    public function setUnmeasured(string $unmeasured): void
    {
        $this->unmeasured = $unmeasured;
    }

    public function getDirPort(): int
    {
        return $this->dirPort;
    }

    public function setDirPort(int $dirPort): void
    {
        $this->dirPort = $dirPort;
    }

    public function getDefaultPolicy(): string
    {
        return $this->defaultPolicy;
    }

    public function setDefaultPolicy(string $defaultPolicy): void
    {
        $this->defaultPolicy = $defaultPolicy;
    }

    public function getPortList(): string
    {
        return $this->portList;
    }

    public function setPortList(string $portList): void
    {
        $this->portList = $portList;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }
}