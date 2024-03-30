<?php

namespace App\Models;
use App\Http\Model;
use App\Http\Validation;
use App\Http\Request;
use App\Http\Middlewares\Middleware;

class Token extends Model {
	protected string $table = 'user_tokens';
	public function generate_tokens(): array {
		$selector = bin2hex(random_bytes(16));
		$validator = bin2hex(random_bytes(32));
		return [$selector, $validator, $selector.':'.$validator];
	}
	public function parse_token(string $token): ?array {
		$parts = explode(':', $token);
		if ($parts && count($parts) == 2) {
			return [$parts[0],$parts[1]];
		}
		return null;
	}
	public function insert_user_token(int $user_id, string $selector, string $hashed_validator, string $expiry) {
		$token['user_id'] = $user_id;
		$token['selector'] = $selector;
		$token['hashed_validator'] = $hashed_validator;
		$token['expiry'] = $expiry;
		$this->create($token);
	}
	public function find_user_by_token(string $token) {
		$tokens = $this->parse_token($token);
		if (!$tokens) {
			return null;
		}

		$sql = "SELECT * FROM users INNER JOIN user_tokens ON user_id = users.id
				WHERE selector = :selector AND expiry > now() LIMIT 1";
		return $this->sql($sql,['selector' => $tokens[0]]);
	}
	public function find_user_token_by_selector(string $selector) {
		return $this->get(['selector' => $selector]);
	}
	public function delete_user_token(int $user_id): bool {
		return $this->delete($user_id);
	}
	public function log_user_in(array $user): bool {
		$_SESSION['loggedin'] = true;
		$_SESSION['user_id'] = $user['id'];
		return true;
	}
	public function remember_me(int $user_id, int $day = 30) {
		[$selector,$validator,$token] = $this->generate_tokens();
		$this->delete_user_token($user_id);
		$expired_seconds = time()+60*60*24*$day;
		$hash_validator = password_hash($validator,PASSWORD_BCRYPT);
		$expiry = date('Y-m-d H:i:s',$expired_seconds);
		$this->insert_user_token($user_id,$selector,$hash_validator,$expiry);
		setcookie('remember_me',$token,$expired_seconds,'/');	
	}
}