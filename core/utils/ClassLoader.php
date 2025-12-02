<?php

class ClassLoader
{
    public static function loadClass()
    {
        spl_autoload_register(function ($class_name) {
            // Caminho base para a pasta 'core'
            $base_dir = ABSPATH . '/core/';

            // Caminho completo do arquivo baseado no nome da classe
            $file = self::findFile($base_dir, $class_name . '.php');

            // Se o arquivo não for encontrado, chama o ErrorController
            if (!$file) {
                require_once ABSPATH . '/core/controllers/ErrorController.php';
                $errorController = new ErrorController();
                $errorController->index();
                return;
            }

            // Requer o arquivo encontrado
            require_once $file;
        });
    }

    private static function findFile($dir, $filename)
    {
        // Verifica todos os arquivos e pastas no diretório atual
        foreach (scandir($dir) as $file) {
            // Ignora '.' e '..'
            if ($file === '.' || $file === '..') {
                continue;
            }

            $fullPath = $dir . '/' . $file;

            // Se for um diretório, faz a busca recursiva
            if (is_dir($fullPath)) {
                $found = self::findFile($fullPath, $filename);
                if ($found) {
                    return $found;
                }
            }

            // Se for um arquivo, verifica se o nome coincide
            if (is_file($fullPath) && basename($fullPath) === $filename) {
                return $fullPath;
            }
        }

        // Retorna null se o arquivo não for encontrado
        return null;
    }
}
