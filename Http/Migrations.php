<?php

namespace App\Http;

class Migrations extends Model {
	public const array TABLES = ["users","user_tokens"];
	private const array MIGRATION = [
		self::TABLES[0] => 'user_table',
		self::TABLES[1] => 'user_tokens_table',
	];
	private function user_table() {
		$this->sql("CREATE TABLE ".self::TABLES[0]." (
			id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			username VARCHAR(255) NOT NULL,
			email VARCHAR(255) NOT NULL,
			is_active BOOLEAN DEFAULT TRUE,
			is_admin BOOLEAN DEFAULT FALSE,
			password VARCHAR(255) NOT NULL);");
	}
	private function user_tokens_table() {
		$this->sql("CREATE TABLE ".self::TABLES[1]." (
			id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			selector VARCHAR(255) NOT NULL,
			hashed_validator VARCHAR(255) NOT NULL,
			user_id INT NOT NULL,
			expiry DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE);");
	}

	public function table_already_exists(string $table_name): bool {
		try {
			$table = $this->sql("SELECT 1 FROM $table_name LIMIT 1");
			return true; 
		} catch (\PDOException $e) {
			return false;
		}
	}
	public function migrate(string $table) {
		$function = self::MIGRATION[$table];
		call_user_func(array((new $this),$function));
	}
}