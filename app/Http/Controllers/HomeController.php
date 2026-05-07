<?php

namespace App\Http\Controllers;

use App\Middleware\AuthMiddleware;

class HomeController extends Controller
{
    public function __construct(AuthMiddleware $authMiddleware)
    {
        parent::__construct($authMiddleware);
    }

    public function index(): void
    {
        $this->requireLogin();
        $nombreUsuario = $this->currentUser();
        $esAdministrador = $nombreUsuario === 'Administrador';
        $this->renderWithLayout('home/index.php', compact('nombreUsuario', 'esAdministrador'));
    }

    public function alias(): void
    {
        $this->redirect('');
    }
}
