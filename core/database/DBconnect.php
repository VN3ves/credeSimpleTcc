<?php

class DBconnect
{
    public $host = 'localhost';
    public $db_name = 'tix1_sistema';
    public $password = '';
    public $user = 'root';
    public $charset = 'utf8mb4';
    public $debug = false;

    public $pdo = null;
    public $error = null;
    public $last_id = null;

    public function __construct($host = null, $db_name = null, $password = null, $user = null, $charset = null, $debug = null)
    {
        $this->host = $host;
        $this->db_name = $db_name;
        $this->password = $password;
        $this->user = $user;
        $this->charset = $charset;
        $this->debug = $debug;

        $this->connect();
    }

    final protected function connect()
    {
        $pdo_details  = "mysql:host={$this->host};";
        $pdo_details .= "dbname={$this->db_name};";
        $pdo_details .= "charset={$this->charset};";

        try {
            $this->pdo = new PDO($pdo_details, $this->user, $this->password);

            if ($this->debug === true) {
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            }
            unset($this->host);
            unset($this->db_name);
            unset($this->password);
            unset($this->user);
            unset($this->charset);
        } catch (PDOException $e) {
            if ($this->debug === true) {
                echo "Erro: " . $e->getMessage();
            }

            die();
        }
    }

}
