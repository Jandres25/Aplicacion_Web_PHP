<?php

namespace Core;

class ErrorPage

{
    public static function render($projectRoot, $publicBaseUrl, int $statusCode, string $customMessage = '')
    {
        $statusCode = (int)$statusCode;
        $statusMap = self::statusMap();
        $statusData = $statusMap[$statusCode] ?? $statusMap[500];

        http_response_code($statusCode > 0 ? $statusCode : 500);
        if (!headers_sent()) {
            header('Content-Type: text/html; charset=UTF-8');
        }

        View::render('errors/http_error.php', [
            'public_base'  => rtrim((string)$publicBaseUrl, '/') . '/',
            'errorCode'    => $statusCode,
            'errorTitle'   => (string)$statusData['title'],
            'errorMessage' => trim((string)$customMessage) !== '' ? trim((string)$customMessage) : (string)$statusData['message'],
        ]);
        exit();
    }

    private static function statusMap()
    {
        return [
            400 => ['title' => 'Solicitud inválida', 'message' => 'La solicitud enviada no pudo ser procesada.'],
            401 => ['title' => 'No autorizado', 'message' => 'Debe autenticarse para acceder a este recurso.'],
            403 => ['title' => 'Acceso denegado', 'message' => 'No tiene permisos para acceder a este recurso.'],
            404 => ['title' => 'Página no encontrada', 'message' => 'La ruta solicitada no existe o fue movida.'],
            405 => ['title' => 'Método no permitido', 'message' => 'El método HTTP utilizado no está permitido para esta ruta.'],
            500 => ['title' => 'Error interno del servidor', 'message' => 'Ocurrió un problema inesperado. Intente nuevamente en unos minutos.'],
            503 => ['title' => 'Servicio no disponible', 'message' => 'El servicio no está disponible temporalmente.'],
        ];
    }
}
