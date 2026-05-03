<?php

namespace Core;

class Router
{
    private $projectRoot;
    private $publicRoot;
    private $publicBaseUrl;
    private $routes = [];

    public function __construct($projectRoot, $publicDirectory = 'public', $publicBaseUrl = null)
    {
        $resolvedRoot = realpath($projectRoot);
        $this->projectRoot = $resolvedRoot !== false ? rtrim($resolvedRoot, '/') : rtrim($projectRoot, '/');
        $publicPath = $this->projectRoot . '/' . trim((string)$publicDirectory, '/');
        $resolvedPublicRoot = realpath($publicPath);
        $this->publicRoot = $resolvedPublicRoot !== false ? rtrim($resolvedPublicRoot, '/') : rtrim($publicPath, '/');
        $fallbackPublicBaseUrl = defined('PUBLIC_PATH') ? (string)PUBLIC_PATH : '/public/';
        $this->publicBaseUrl = rtrim((string)($publicBaseUrl ?? $fallbackPublicBaseUrl), '/') . '/';
    }

    public function get($path, callable $handler)
    {
        $this->add('GET', $path, $handler);
        return $this;
    }

    public function post($path, callable $handler)
    {
        $this->add('POST', $path, $handler);
        return $this;
    }

    public function add($methods, $path, callable $handler)
    {
        $normalizedPath = $this->normalizeRoutePath($path);
        $methods = is_array($methods) ? $methods : [$methods];

        foreach ($methods as $method) {
            $normalizedMethod = strtoupper((string)$method);
            if ($normalizedMethod === '') {
                continue;
            }
            $this->routes[$normalizedMethod][$normalizedPath] = $handler;
        }

        return $this;
    }

    public function dispatch($requestUri, $scriptName, $requestMethod = null)
    {
        $path = $this->extractPath($requestUri, $scriptName);

        $staticFile = $this->resolveStatic($path);
        if ($staticFile !== null) {
            $this->serveStatic($staticFile);
            return;
        }

        $method = strtoupper((string)($requestMethod ?? ($_SERVER['REQUEST_METHOD'] ?? 'GET')));
        if ($method === 'HEAD' && isset($this->routes['GET'][$path])) {
            $method = 'GET';
        }

        if (isset($this->routes[$method][$path])) {
            call_user_func($this->routes[$method][$path]);
            return;
        }

        $allowedMethods = $this->allowedMethodsForPath($path);
        if (!empty($allowedMethods)) {
            header('Allow: ' . implode(', ', $allowedMethods));
            ErrorPage::render($this->projectRoot, $this->publicBaseUrl, 405);
        }

        ErrorPage::render($this->projectRoot, $this->publicBaseUrl, 404);
    }

    private function allowedMethodsForPath($path)
    {
        $allowedMethods = [];
        foreach ($this->routes as $httpMethod => $routesForMethod) {
            if (isset($routesForMethod[$path])) {
                $allowedMethods[] = $httpMethod;
            }
        }

        sort($allowedMethods);
        return $allowedMethods;
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
        $uriPath = preg_replace('#/+#', '/', (string)$uriPath);
        $uriPath = str_replace('\\', '/', $uriPath);

        if ($uriPath === null || strpos($uriPath, '..') !== false) {
            return '/';
        }

        if ($uriPath === '') {
            return '/';
        }

        if ($uriPath[0] !== '/') {
            $uriPath = '/' . $uriPath;
        }

        if ($uriPath !== '/' && substr($uriPath, -1) === '/') {
            $uriPath = rtrim($uriPath, '/');
        }

        return $uriPath;
    }

    private function resolveStatic($path)
    {
        $normalizedPath = ltrim((string)$path, '/');
        if ($normalizedPath === '') {
            return null;
        }

        $candidates = [$normalizedPath, $normalizedPath . '/index.html'];
        foreach ($candidates as $candidate) {
            $file = $this->resolvePublicPath($candidate);
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

    private function resolvePublicPath($relativePath)
    {
        $relativePath = ltrim((string)$relativePath, '/');
        if ($relativePath === '') {
            return null;
        }

        $fullPath = $this->publicRoot . '/' . $relativePath;
        if (!file_exists($fullPath)) {
            return null;
        }

        $resolvedPath = realpath($fullPath);
        if ($resolvedPath === false) {
            return null;
        }

        if ($resolvedPath !== $this->publicRoot && strpos($resolvedPath, $this->publicRoot . '/') !== 0) {
            return null;
        }

        return $resolvedPath;
    }

    private function normalizeRoutePath($path)
    {
        $path = trim((string)$path);
        if ($path === '') {
            return '/';
        }

        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        $path = preg_replace('#/+#', '/', $path);
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }

        return $path;
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
