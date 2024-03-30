<?php

namespace App\Http;

class Cookie {
	public static function is_set(string $cookie) {
		return isset($_COOKIE[$cookie]);
	}
	public static function get_cookie(string $cookie) {
		return $_COOKIE[$cookie];
	}
	public static function unset_cookie(string $cookie) {
		unset($_COOKIE[$cookie]);
	}
	public static function delete_cookie(string $cookie) {
		setcookie($cookie,null,-1,'/');
	}
}