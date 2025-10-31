<?php

class DBmethods
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
		$this->host = $host ? $host : HOSTNAME;
		$this->db_name = $db_name ? $db_name : DB_NAME;
		$this->password = $password ? $password : DB_PASSWORD;
		$this->user = $user ? $user : DB_USER;
		$this->charset = $charset ? $charset : DB_CHARSET;
		$this->debug = $debug ? $debug : DEBUG;

		$db = new DBconnect($this->host, $this->db_name, $this->password, $this->user, $this->charset, $this->debug);
		$this->pdo = $db->pdo;
	}

	public function query($stmt, $data_array = null)
	{
		$query = $this->pdo->prepare($stmt);
		$check_exec = $query->execute($data_array);

		if ($check_exec) {
			return $query;
		} else {
			$error = $query->errorInfo();
			$this->error = $error[2];

			return false;
		}
	}

	/**
	 * Verifica se um banco de dados existe no servidor MySQL.
	 */
	function databaseExists(string $dbName): bool
	{
		// Usa credenciais padrão, mas SEM informar dbname
		$dsn = sprintf(
			"mysql:host=%s;charset=%s",
			HOSTNAME,
			DB_CHARSET
		);

		try {
			$pdo = new PDO($dsn, DB_USER, DB_PASSWORD, [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]);
			$stmt = $pdo->prepare(
				"SELECT SCHEMA_NAME
             FROM INFORMATION_SCHEMA.SCHEMATA
             WHERE SCHEMA_NAME = ?"
			);
			$stmt->execute([$dbName]);
			return (bool) $stmt->fetchColumn();
		} catch (PDOException $e) {
			// Se der qualquer erro de conexão, considere como não existente
			Log::error("Banco de dados “{$dbName}” não encontrado.");
			return false;
		}
	}

	public function insert($table)
	{
		$cols = array();

		$place_holders = '(';

		$values = array();

		$j = 1;

		$data = func_get_args();

		if (!isset($data[1]) || !is_array($data[1])) {
			return;
		}

		for ($i = 1; $i < count($data); $i++) {
			foreach ($data[$i] as $col => $val) {
				if ($i === 1) {
					$cols[] = "`$col`";
				}

				if ($j <> $i) {
					$place_holders .= '), (';
				}

				$place_holders .= '?, ';

				$values[] = $val;

				$j = $i;
			}

			$place_holders = substr($place_holders, 0, strlen($place_holders) - 2);
		}

		$cols = implode(', ', $cols);

		$stmt = "INSERT INTO `$table` ($cols) VALUES $place_holders) ";

		$insert = $this->query($stmt, $values);

		if ($insert) {
			if (method_exists($this->pdo, 'lastInsertId') && $this->pdo->lastInsertId()) {
				$this->last_id = $this->pdo->lastInsertId();
			}

			return $insert;
		}

		return;
	}

	public function lastInsertId()
	{
		if (method_exists($this->pdo, 'lastInsertId') && $this->pdo->lastInsertId()) {
			return $this->last_id = $this->pdo->lastInsertId();
		} else {
			return;
		}
	}

	public function update($table, $where_field, $where_field_value, $values)
	{
		if (empty($table) || empty($where_field) || empty($where_field_value)) {
			return;
		}

		$stmt = " UPDATE `$table` SET ";

		$set = array();

		$where = " WHERE `$where_field` = ? ";

		if (!is_array($values)) {
			return;
		}

		foreach ($values as $column => $value) {
			$set[] = " `$column` = ?";
		}

		$set = implode(', ', $set);

		$stmt .= $set . $where;

		$values[] = $where_field_value;

		$values = array_values($values);

		$update = $this->query($stmt, $values);

		if ($update) {
			return $update;
		}

		return;
	}

	public function delete($table, $where_field, $where_field_value)
	{
		if (empty($table) || empty($where_field) || empty($where_field_value)) {
			return;
		}

		$stmt = " DELETE FROM `$table` ";

		$where = " WHERE `$where_field` = ? ";

		$stmt .= $where;

		$values = array($where_field_value);

		$delete = $this->query($stmt, $values);

		if ($delete) {
			return $delete;
		}

		return;
	}

	public function error()
	{
		return $this->error;
	}
}
