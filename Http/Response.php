<?php

namespace App\Http;
use App\Http\Middlewares\Middleware;
use App\Http\Validation;
use App\Models\Comment;

class Response {
	public function __construct(
		private string $html = '',
		private ?array $content = [],
		private int $status = 200,
	) {}

	public function send() {
		if (!empty($this->html)) {
			$this->content['user'] = Request::user();
			$this->content['url'] = Request::url();
			extract($this->content);
			return require BASE_PATH . "/views/{$this->html}.view.php";
		} else {
			return Middleware::NOTHING;
		}
	}

	public static function status_code($code = 404) {
		return http_response_code($code);
	}

	public static function redirect($url = '', $replace = true, $status = 303) {
		$url = $_SERVER['HTTP_ORIGIN'] . '/' . $url;
		return header("location: {$url}", $replace, $status);
	}

}