<?php

namespace App\Http;
use App\Models\User;
use App\Http\Middlewares\Middleware;

class Request {
	public function __construct(
		public readonly array $getParams,
		public readonly array $postParams,
		public readonly array $cookies,
		public readonly array $files,
		public readonly array $server
	){}
	public static function createFromGlobals(): static {
		return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
	}


	public static function path(): string 	{
		return strtok($_SERVER['REQUEST_URI'], '?');
	}

	public function method(): string {
		return $this->server['REQUEST_METHOD'];
	}

	public static function _post(): array {
		return $_POST;
	}

	public static function _files(): array {
		return $_FILES;
	}

	public static function user() {
		if (isset($_SESSION['loggedin'])){
			$object = new User();
			$user = $object->get(['id' => $_SESSION['user_id']]);
			return $user;
		} 
		return Middleware::ANONYMOUS;	
	}
	public static function is_admin($user) {
		if (isset($_SESSION['loggedin']) && is_array($user)) {
			if ($user['is_admin'] == 1) {return true;}
		} return false;
	}

	public static function server() {
		return $_SERVER;
	}

	public static function IP() {
		return $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'localhost';
	}
	public static function AGENT() {
		return $_SERVER['HTTP_USER_AGENT'];
	}

	public static function save_file($file, $dir = Middleware::MEDIA) {
		$file = Validation::file($file);
		$full_path = $dir . $file['name'];
		if (self::is_valid_file_type($file)) {
			move_uploaded_file($file['tmp_name'],$full_path);
			return $full_path;
		} return false;
	}
	public static function is_valid_file_type(array $file) {
		$valid_types = ['png','jpg','jpeg','mp4','webm'];
		$image_type = explode('/', $file['type'])[1];
		if (in_array($image_type,$valid_types)) {
			return true;
		}
		return false;

	}
	public static function back(){
		return $_SERVER['HTTP_REFERER'];
	}
	public static function url() {
		return (empty($_SERVER['HTTP_X_FORWARDED_PROTO']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";
	}
	public static function request_uri() {
		return parse_url($_SERVER['REQUEST_URI'])['path'];
	}
}