<?php

namespace App\Http\Middlewares;
use App\Http\Request;
use App\Models\Token;
use App\Models\User;
use App\Http\Cookie;

class RememberMe {
	public function handle() {
		if (Cookie::is_set(Middleware::REMEMBER_ME)) {
			$token = new Token();
			$user = $token->find_user_by_token(Cookie::get_cookie(Middleware::REMEMBER_ME));
			if ($user) {
				$token->log_user_in($user);	
			}
		}
	}
}