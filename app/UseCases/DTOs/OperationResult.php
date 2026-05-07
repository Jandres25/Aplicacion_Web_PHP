<?php

namespace App\UseCases\DTOs;

class OperationResult
{
    public function __construct(
        public readonly bool    $success,
        public readonly ?string $message = null,
        public readonly mixed   $data    = null
    ) {}

    public static function ok(?string $message = null, mixed $data = null): self
    {
        return new self(true, $message, $data);
    }

    public static function fail(string $message): self
    {
        return new self(false, $message);
    }
}
