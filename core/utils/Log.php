<?php

class Log
{
    private static $logDir = LOG_DIR ? LOG_DIR : __DIR__ . '/../logs';

    private static function writeLog($level, $message)
    {
        // Cria o diretório se não existir
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0777, true);
        }

        // Verifica se message é um array e converte para string
        if (is_array($message)) {
            $message = json_encode($message);
        }

        // Define o nome do arquivo de log baseado na data atual
        $logFile = self::$logDir . '/' . date('Y-m-d') . '.log';

        $dateTime = date('Y-m-d H:i:s');
        $logMessage = "[$dateTime] [$level] $message" . PHP_EOL;

        if (file_exists($logFile)) {
            // Checa se o arquivo não tem permissão 0777
            if ((fileperms($logFile) & 0x1FF) !== 0777) {
                chmod($logFile, 0777);
            }
        }
        
        // Escreve a mensagem no arquivo de log
        file_put_contents($logFile, $logMessage, FILE_APPEND);

        // Remove logs antigos
        self::cleanOldLogs();
    }

    public static function info($message)
    {
        self::writeLog('INFO', $message);
    }

    public static function error($message)
    {
        self::writeLog('ERROR', $message);
    }

    public static function warning($message)
    {
        self::writeLog('WARNING', $message);
    }

    public static function debug($message)
    {
        self::writeLog('DEBUG', $message);
    }

    public static function critical($message)
    {
        self::writeLog('CRITICAL', $message);
    }

    private static function cleanOldLogs()
    {
        $files = glob(self::$logDir . '/*.log');
        $now = time();

        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= 7 * 24 * 60 * 60) { // 7 dias em segundos
                    unlink($file);
                }
            }
        }
    }

    public static function handleException($exception)
    {
        self::error($exception->getMessage() . "\n" . $exception->getTraceAsString());
    }
}
