<?php
declare(strict_types=1);

namespace Gared\TorDirectoryClient\Model;

class Flags
{
    public const FAST = 'Fast';
    public const HS_DIR = 'HSDir';
    public const RUNNING = 'Running';
    public const STABLE ='Stable';
    public const V2_DIR = 'V2Dir';
    public const VALID = 'Valid';
    public const STALE_DESC = 'StaleDesc';
    public const EXIT = 'Exit';
    public const GUARD = 'Guard';

    private array $flags;

    public function __construct(array $flags)
    {
        $this->flags = $flags;
    }

    public function isFast(): bool
    {
        return in_array(self::FAST, $this->flags, true);
    }

    public function isHsDir(): bool
    {
        return in_array(self::HS_DIR, $this->flags, true);
    }

    public function isRunning(): bool
    {
        return in_array(self::RUNNING, $this->flags, true);
    }

    public function isStable(): bool
    {
        return in_array(self::STABLE, $this->flags, true);
    }

    public function isV2Dir(): bool
    {
        return in_array(self::V2_DIR, $this->flags, true);
    }

    public function isValid(): bool
    {
        return in_array(self::VALID, $this->flags, true);
    }

    public function isStaleDesc(): bool
    {
        return in_array(self::STALE_DESC, $this->flags, true);
    }

    public function isExit(): bool
    {
        return in_array(self::EXIT, $this->flags, true);
    }

    public function isGuard(): bool
    {
        return in_array(self::GUARD, $this->flags, true);
    }
}