<?php

class Router
{
    private $projectRoot;

    public function __construct($projectRoot)
    {
        $resolvedRoot = realpath($projectRoot);
        $this->projectRoot = $resolvedRoot !== false ? rtrim($resolvedRoot, '/') : rtrim($projectRoot, '/');
    }

    public function dispatch($requestUri, $scriptName)
    {
        $path = $this->extractPath($requestUri, $scriptName);
        if ($path === '') {
            $path = 'index';
        }

        $staticFile = $this->resolveStatic($path);
        if ($staticFile !== null) {
            $this->serveStatic($staticFile);
            return;
        }

        $phpFile = $this->resolvePhp($path);
        if ($phpFile !== null) {
            $this->executePhp($phpFile);
            return;
        }

        http_response_code(404);
        echo '404 Not Found';
    }

    private function extractPath($requestUri, $scriptName)
    {
        $uriPath = parse_url($requestUri, PHP_URL_PATH);
        $uriPath = $uriPath === null ? '/' : $uriPath;

        $basePath = dirname($scriptName);
        $basePath = str_replace('\\', '/', $basePath);
        $basePath = $basePath === '.' ? '' : rtrim($basePath, '/');

        if ($basePath !== '' && strpos($uriPath, $basePath) === 0) {
            $uriPath = substr($uriPath, strlen($basePath));
        }

        $uriPath = rawurldecode($uriPath);
        $uriPath = trim($uriPath, '/');
        $uriPath = preg_replace('#/+#', '/', $uriPath);
        $uriPath = str_replace('\\', '/', $uriPath);

        if ($uriPath === null || strpos($uriPath, '..') !== false) {
            return '';
        }

        return $uriPath;
    }

    private function resolveStatic($path)
    {
        $candidates = [$path, $path . '/index.html'];

        foreach ($candidates as $candidate) {
            $file = $this->resolveExistingPath($candidate);
            if ($file === null || !is_file($file)) {
                continue;
            }

            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($extension, ['php', 'phtml', 'phar'], true)) {
                continue;
            }

            $basename = basename($file);
            if (strpos($basename, '.') === 0) {
                continue;
            }

            return $file;
        }

        return null;
    }

    private function resolvePhp($path)
    {
        $candidates = [];
        if (preg_match('/\.php$/i', $path)) {
            $candidates[] = $path;
        } else {
            $candidates[] = $path . '.php';
            $candidates[] = $path . '/index.php';
        }

        foreach ($candidates as $candidate) {
            $file = $this->resolveExistingPath($candidate);
            if ($file !== null && is_file($file)) {
                return $file;
            }
        }

        return null;
    }

    private function resolveExistingPath($relativePath)
    {
        $relativePath = ltrim($relativePath, '/');
        if ($relativePath === '') {
            return null;
        }

        $fullPath = $this->projectRoot . '/' . $relativePath;
        if (!file_exists($fullPath)) {
            return null;
        }

        $resolvedPath = realpath($fullPath);
        if ($resolvedPath === false) {
            return null;
        }

        if ($resolvedPath !== $this->projectRoot && strpos($resolvedPath, $this->projectRoot . '/') !== 0) {
            return null;
        }

        return $resolvedPath;
    }

    private function executePhp($file)
    {
        $currentDirectory = getcwd();
        chdir(dirname($file));
        require $file;
        if ($currentDirectory !== false) {
            chdir($currentDirectory);
        }
    }

    private function serveStatic($file)
    {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $mimes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'pdf' => 'application/pdf',
            'txt' => 'text/plain',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject'
        ];

        $contentType = isset($mimes[$extension]) ? $mimes[$extension] : 'application/octet-stream';
        header('Content-Type: ' . $contentType);
        $size = filesize($file);
        if ($size !== false) {
            header('Content-Length: ' . (string)$size);
        }
        readfile($file);
    }
}
