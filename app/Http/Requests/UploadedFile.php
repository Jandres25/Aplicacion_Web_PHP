<?php

namespace App\Http\Requests;

class UploadedFile
{
    public function __construct(
        public readonly string $tmpName,
        public readonly string $name,
        public readonly string $mimeType,
        public readonly int    $size,
        public readonly int    $error
    ) {}

    public static function fromArray(array $file): ?self
    {
        if ((int)($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        return new self(
            (string)($file['tmp_name'] ?? ''),
            (string)($file['name']     ?? ''),
            (string)($file['type']     ?? ''),
            (int)($file['size']        ?? 0),
            (int)($file['error']       ?? UPLOAD_ERR_OK)
        );
    }
}
