<?php
ob_start();

if(!defined('ABSPATH')) exit;
 
session_start();

if(!defined('DEBUG') || DEBUG === false){
	error_reporting(0);
	ini_set("display_errors", 0); 
}else{
	error_reporting(E_ALL);
	ini_set("display_errors", 1); 
	
}
require_once ABSPATH . '/core/utils/Log.php';
// Define um manipulador para exceções não tratadas
set_exception_handler([Log::class, 'handleException']);

// Define um manipulador para erros PHP
set_error_handler(function ($severity, $message, $file, $line) {
    Log::error("Erro: $message em $file na linha $line");
});

// Define um manipulador para shutdown (erros fatais)
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error) {
        Log::critical("Erro fatal: {$error['message']} em {$error['file']} na linha {$error['line']}");
    }
});
require_once ABSPATH . '/core/utils/ClassLoader.php';
ClassLoader::loadClass();

require_once ABSPATH . '/core/utils/global-functions.php';

$AppMVC = new MVC();
