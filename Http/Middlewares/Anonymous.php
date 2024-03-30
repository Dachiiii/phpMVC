<?php

namespace App\Http\Middlewares;
use App\Http\Request;
use App\Http\Response;

class Anonymous {
	public function handle() {
		if (Request::user() != Middleware::ANONYMOUS) {
			Response::status_code(403);
			exit;
		}
	}
}