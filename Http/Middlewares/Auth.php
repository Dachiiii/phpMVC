<?php

namespace App\Http\Middlewares;
use App\Http\Request;

class Auth {
	public function handle() {
		if (Request::user() === Middleware::ANONYMOUS) {
			header('location: /login');
			exit();
		}		
	}
}