<?php
class DBInstance
{
    private static $instance = null;

    // Impede a criação direta da classe
    private function __construct() {}

    // Retorna a instância única de DBmethods
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new DBmethods();
        }

        return self::$instance;
    }
}
