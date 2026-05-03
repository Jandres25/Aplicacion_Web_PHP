<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
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
