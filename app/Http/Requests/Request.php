<?php

namespace App\Http\Requests;

abstract class Request
{
    abstract public static function fromArray(array $data): static;

    /** @return array<string,string> field => error message */
    abstract public function validate(): array;

    protected static function str(array $data, string $key): string
    {
        return trim((string)($data[$key] ?? ''));
    }

    protected static function nullableStr(array $data, string $key): ?string
    {
        $val = trim((string)($data[$key] ?? ''));
        return $val === '' ? null : $val;
    }

    protected static function int(array $data, string $key): int
    {
        return (int)($data[$key] ?? 0);
    }

    protected static function bool(array $data, string $key): bool
    {
        return !empty($data[$key]);
    }
}
