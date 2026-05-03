<?php

namespace App\Http\Controllers;

use App\Middleware\AuthMiddleware;
use Core\Flash;
use Core\Security;
use Core\View;

abstract class Controller
{
    protected string $projectRoot;
    protected string $publicBaseUrl;
    protected string $uploadsDirectory;

    public function __construct(string $projectRoot, string $publicBaseUrl)
    {
        $this->projectRoot      = rtrim($projectRoot, '/');
        $this->publicBaseUrl    = rtrim($publicBaseUrl, '/') . '/';
        $this->uploadsDirectory = $this->projectRoot . '/public/storage/uploads';
    }

    protected function renderWithLayout(string $viewFile, array $data = []): void
    {
        View::renderWithLayout($viewFile, $data);
    }

    protected function pageHeaderData(string $title, string $icon, array $breadcrumbs): array
    {
        return [
            'pageHeaderTitle' => $title,
            'pageHeaderIcon'  => $icon,
            'pageBreadcrumbs' => $breadcrumbs,
        ];
    }

    protected function moduleBreadcrumbs(
        string $moduleLabel,
        string $moduleRoute,
        string $moduleIcon,
        string $currentLabel = '',
        string $currentIcon = ''
    ): array {
        $breadcrumbs = [[
            'label' => 'Inicio',
            'href'  => $this->publicBaseUrl,
            'icon'  => 'fas fa-house',
        ]];

        if ($currentLabel === '') {
            $breadcrumbs[] = ['label' => $moduleLabel, 'icon' => $moduleIcon, 'active' => true];
            return $breadcrumbs;
        }

        $breadcrumbs[] = [
            'label' => $moduleLabel,
            'href'  => $this->publicBaseUrl . ltrim($moduleRoute, '/'),
            'icon'  => $moduleIcon,
        ];
        $breadcrumbs[] = ['label' => $currentLabel, 'icon' => $currentIcon, 'active' => true];

        return $breadcrumbs;
    }

    protected function requireLogin(): void
    {
        (new AuthMiddleware())->requireLogin($this->publicBaseUrl . 'login');
    }

    protected function currentUser(): string
    {
        return (string)(new AuthMiddleware())->currentUser();
    }

    protected function requireAdmin(): void
    {
        if ($this->currentUser() !== 'Administrador') {
            Flash::set('No tiene permisos para acceder a esta sección.', 'error');
            $this->redirect('');
        }
    }

    protected function redirect(string $route = ''): never
    {
        header('Location:' . $this->publicBaseUrl . ltrim($route, '/'));
        exit();
    }

    protected function hasValidCsrfToken(array $request): bool
    {
        return Security::isValidCsrfToken($request['csrf_token'] ?? null);
    }

    protected function isAjaxRequest(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
