<?php

namespace Config;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class AppLogger
{
    private static ?LoggerInterface $instance = null;

    private function __construct() {}

    public static function getInstance(): LoggerInterface
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $logger  = new Logger('app');
        $logsDir = dirname(__DIR__) . '/storage/logs';

        if (!is_dir($logsDir)) {
            mkdir($logsDir, 0755, true);
        }

        $isLocal = ($_ENV['APP_ENV'] ?? 'local') === 'local';

        // Rotar logs cada día, mantener 14 archivos
        $logger->pushHandler(new RotatingFileHandler(
            $logsDir . '/app.log',
            14,
            $isLocal ? Level::Debug : Level::Warning
        ));

        // En local, mostrar también en stderr (visible en Apache error_log)
        if ($isLocal) {
            $logger->pushHandler(new StreamHandler('php://stderr', Level::Error));
        }

        self::$instance = $logger;

        return self::$instance;
    }

    private function __clone() {}
}
