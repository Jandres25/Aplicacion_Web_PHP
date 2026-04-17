<?php

namespace App\Infrastructure;

class EmployeeFileStorage
{
    public function storeUploadedFile($uploadedFile, $baseDirectory, &$errorMessage = null)
    {
        $errorMessage = null;

        if (!is_array($uploadedFile) || $uploadedFile === []) {
            return '';
        }

        $uploadError = isset($uploadedFile['error']) ? (int)$uploadedFile['error'] : UPLOAD_ERR_OK;
        if ($uploadError === UPLOAD_ERR_NO_FILE) {
            return '';
        }

        if ($uploadError !== UPLOAD_ERR_OK) {
            $errorMessage = $this->uploadErrorToMessage($uploadError);
            return '';
        }

        if (!isset($uploadedFile['tmp_name']) || !is_string($uploadedFile['tmp_name']) || $uploadedFile['tmp_name'] === '') {
            $errorMessage = 'No se recibió el archivo temporal de la subida.';
            return '';
        }

        $originalName = isset($uploadedFile['name']) ? trim((string)$uploadedFile['name']) : '';
        if ($originalName === '') {
            $errorMessage = 'El archivo seleccionado no tiene nombre válido.';
            return '';
        }

        $targetDirectory = $this->ensureDirectory($baseDirectory);
        if ($targetDirectory === null) {
            $errorMessage = 'No se pudo preparar el directorio de almacenamiento.';
            return '';
        }

        if (!is_writable($targetDirectory)) {
            @chmod($targetDirectory, 0777);
            if (!is_writable($targetDirectory)) {
                $errorMessage = 'El directorio de almacenamiento no tiene permisos de escritura.';
                return '';
            }
        }

        if (!is_uploaded_file($uploadedFile['tmp_name'])) {
            $errorMessage = 'El archivo temporal de subida no es válido.';
            return '';
        }

        $safeName = basename($originalName);
        $uniqueName = uniqid('', true) . '_' . $safeName;
        $targetPath = $targetDirectory . '/' . $uniqueName;

        if (!move_uploaded_file($uploadedFile['tmp_name'], $targetPath)) {
            $errorMessage = 'No se pudo mover el archivo al directorio final.';
            return '';
        }

        return 'storage/uploads/' . $uniqueName;
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

        $normalizedPath = $this->normalizeStoredPath($fileName);
        if ($normalizedPath === '') {
            return;
        }

        $candidates = $this->buildDeleteCandidates($targetDirectory, $normalizedPath);
        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                unlink($candidate);
                return;
            }
        }
    }

    private function ensureDirectory($baseDirectory)
    {
        if (!is_string($baseDirectory) || trim($baseDirectory) === '') {
            return null;
        }

        if (!is_dir($baseDirectory) && !mkdir($baseDirectory, 0775, true)) {
            return null;
        }

        $resolved = realpath($baseDirectory);
        return $resolved === false ? null : $resolved;
    }

    private function normalizeStoredPath($fileName)
    {
        $path = trim((string)$fileName);
        if ($path === '') {
            return '';
        }

        $urlPath = parse_url($path, PHP_URL_PATH);
        if (is_string($urlPath) && $urlPath !== '') {
            $path = $urlPath;
        }

        $path = rawurldecode($path);
        $path = str_replace('\\', '/', $path);

        return ltrim($path, '/');
    }

    private function buildDeleteCandidates($targetDirectory, $normalizedPath)
    {
        $candidates = [];
        $safeName = basename($normalizedPath);
        if ($safeName !== '' && $safeName !== '.' && $safeName !== '..') {
            $candidates[] = $targetDirectory . '/' . $safeName;
        }

        $publicRoot = dirname(dirname($targetDirectory));
        $projectRoot = dirname($publicRoot);

        if (strpos($normalizedPath, 'storage/uploads/') === 0) {
            $candidates[] = $publicRoot . '/' . $normalizedPath;
        }

        if (strpos($normalizedPath, 'public/storage/uploads/') === 0) {
            $candidates[] = $projectRoot . '/' . $normalizedPath;
        }

        if (strpos($normalizedPath, 'uploads/') === 0) {
            $candidates[] = dirname($targetDirectory) . '/' . $normalizedPath;
        }

        if (strpos($normalizedPath, 'http://') === 0 || strpos($normalizedPath, 'https://') === 0) {
            return array_values(array_unique($candidates));
        }

        if (strpos($normalizedPath, '/') !== false) {
            $candidates[] = $targetDirectory . '/' . ltrim(str_replace('storage/uploads/', '', $normalizedPath), '/');
        }

        return array_values(array_unique($candidates));
    }

    private function uploadErrorToMessage($uploadError)
    {
        $messages = [
            UPLOAD_ERR_INI_SIZE => 'El archivo excede el tamaño permitido por el servidor.',
            UPLOAD_ERR_FORM_SIZE => 'El archivo excede el tamaño permitido por el formulario.',
            UPLOAD_ERR_PARTIAL => 'El archivo se subió parcialmente, intente nuevamente.',
            UPLOAD_ERR_NO_TMP_DIR => 'El servidor no tiene directorio temporal para subidas.',
            UPLOAD_ERR_CANT_WRITE => 'El servidor no pudo escribir el archivo en disco.',
            UPLOAD_ERR_EXTENSION => 'Una extensión de PHP bloqueó la subida del archivo.',
        ];

        return isset($messages[$uploadError]) ? $messages[$uploadError] : 'La subida del archivo falló por un error desconocido.';
    }
}
