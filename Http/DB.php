<?php

namespace App\Http;
use PDO;

class DB {
	private $connect;
	private $statement;

	public function __construct() {
		$pdo = "mysql:hostname=".$_ENV['DB_HOST'].";dbname=".$_ENV['DB_NAME'];
		$this->connect = new PDO($pdo,$_ENV['DB_USER'],$_ENV['DB_PASSWORD']);
	}

	public function query($query, $attrs = []) {
		$stm = $this->connect->prepare($query);
		$result = $stm->execute($attrs);
		$this->statement = $stm;
		return $this;
	}

	public function fetchall() {
		return $this->statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function fetch() {
		return $this->statement->fetch(PDO::FETCH_ASSOC);
	}
}