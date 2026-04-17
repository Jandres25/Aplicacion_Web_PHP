<?php

namespace App\Infrastructure;

class EmployeeFileStorage
{
    public function storeUploadedFile($uploadedFile, $baseDirectory)
    {
        if (!isset($uploadedFile['tmp_name']) || !is_string($uploadedFile['tmp_name']) || $uploadedFile['tmp_name'] === '') {
            return '';
        }

        $originalName = isset($uploadedFile['name']) ? trim((string)$uploadedFile['name']) : '';
        if ($originalName === '') {
            return '';
        }

        $targetDirectory = realpath($baseDirectory);
        if ($targetDirectory === false) {
            return '';
        }

        $safeName = basename($originalName);
        $uniqueName = time() . '_' . $safeName;
        $targetPath = $targetDirectory . '/' . $uniqueName;

        if (!move_uploaded_file($uploadedFile['tmp_name'], $targetPath)) {
            return '';
        }

        return $uniqueName;
    }

    public function deleteFileIfExists($baseDirectory, $fileName)
    {
        if (!is_string($fileName) || trim($fileName) === '') {
            return;
        }

        $targetDirectory = realpath($baseDirectory);
        if ($targetDirectory === false) {
            return;
        }

        $safeName = basename($fileName);
        $targetPath = $targetDirectory . '/' . $safeName;

        if (is_file($targetPath)) {
            unlink($targetPath);
        }
    }
}
