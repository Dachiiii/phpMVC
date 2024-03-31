<?php

namespace App\Http;

class Model extends DB {

	public function qry($data) {
		$keys = array_keys($data);
		$query = "SELECT * FROM $this->table WHERE ";

		foreach ($keys as $key) {
			$query .= $key . ' = :' . $key . ' && ';
		}
		return trim($query,' && ');
	}
	public function where(array $data) {
		return $this->query($this->qry($data),$data)->fetchall();
	}

	public function create(array $data) {
		$keys = array_keys($data);
		$query = "INSERT INTO $this->table (".implode(',', $keys).") VALUES (:".implode(',:',$keys).")";
		return $this->query($query,$data)->fetch();
	}

	public function all() {
		return $this->query("SELECT * FROM $this->table")->fetchall();
	}

	public function delete(int $id, string $id_field = 'id') {
		$data[$id_field] = $id;
		return $this->query("DELETE FROM $this->table WHERE id = :id",$data)->fetch();
	}

	public function update(int $id, $data, string $id_field = 'id') {
		$query = "UPDATE $this->table SET ";
		foreach (array_keys($data) as $key) {
			$query .= $key . ' = :' . $key . ', ';
		}
		$query = trim($query,', ');
		$query .= " WHERE $id_field = :$id_field";
		// dd($query);
		$data[$id_field] = $id;
		return $this->query($query,$data)->fetch();
	}

	public function get(array $data) {
		return $this->query($this->qry($data),$data)->fetch();
	}

	public function sql(string $sql, $data = []) {	
		return $this->query($sql,$data)->fetch();
	}
	
	public static function validate_fields(array $fillable, array $fields) {
		$keys = [];
		foreach(array_keys($fields) as $key) {
			if (in_array($key, $fillable)) {
				$keys[$key] = $fields[$key];
			}
		}
		return $keys;
	}
}