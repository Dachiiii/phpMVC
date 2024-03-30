<?php
require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;
use App\Http\Migrations;

$env = Dotenv::createImmutable(__DIR__);
$env->load();

$migrations = new Migrations();
foreach ($migrations::TABLES as $table) {
	if (!$migrations->table_already_exists($table)) {
		$migrations->migrate($table);
	} else {
		echo "Table '{$table}' Already Exists.\n";
	}
}
