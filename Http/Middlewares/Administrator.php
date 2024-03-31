<?php

namespace App\Http\Middlewares;
use App\Http\Request;
use App\Http\Response;
use App\Models\User;

class Administrator {
	public function handle() {
		$user = Request::user();
		if (!(is_array($user)) || (is_array($user)) && $user['is_admin'] == 0) {
			Response::status_code(404);
			exit();
		}
	}
}