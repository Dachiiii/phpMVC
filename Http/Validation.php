<?php

namespace App\Http;
use App\Http\Middlewares\Middleware;
use App\Models\AnonymousPost;

class Validation {

	public const string UNIQUE = 'UNIQUE';
	public const string PASSWORDS_NOT_MATCHING = "Passwords Not Matching";
	public const string INCORRECT_PASSWORD = "Incorrent Password";

	public static function match(string $string1, string $string2) {
		return $string1 === $string2;
	}

	public static function get_file_size($file) {
		$size = log(filesize($file)) / log(1024);
		$f_base = floor($size);
		$suffix = ["", "KB", "MB"];
		return round(pow(1024,$size - floor($size)),1) . $suffix[$f_base];
	}

	public static function get_file_dimensions($file) {
		$dimension = getimagesize($file);
		return $dimension[0] . 'x' . $dimension[1];
	}


	public static function validate_string(string $string, $min = 1, $max = INF) {
		if ($string) {
			if (strlen($string) > $max || strlen($string) < $min) {
				return false;
			}
		}
		return true;
	}
}