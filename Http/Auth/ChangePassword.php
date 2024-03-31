<?php

namespace App\Http\Auth;
use App\Http\Validation;
use App\Models\ResetToken;
use App\Models\User;

class ChangePassword {
	protected const string PASSWORD1_FIELD = 'password1';
	protected const string PASSWORD2_FIELD = 'password2';
	private string $token;

	public function __construct(string $token) {
		$this->token = $token;
	}	
	private array $valid = [
		self::PASSWORD1_FIELD,
		self::PASSWORD2_FIELD,
	];

	private function is_valid_request(array $input) {
		foreach(array_keys($input) as $i) {
			if (!in_array($i, $this->valid)) {
				return false;
			}
		} return true;
	}
	public function change(array $request) {
		$token_object = new ResetToken();
		$user = $token_object->get_user_by_reset_token($this->token);
		if ($user) {
			if ($this->is_valid_request($request)) {
				if (Validation::match($request[self::PASSWORD1_FIELD], $request[self::PASSWORD2_FIELD])) {
					$userObj = new User();
					$password = password_hash($request[self::PASSWORD1_FIELD], PASSWORD_BCRYPT);
					$userObj->update($user['id'],['password' => $password]);
					$_token = $token_object->get(['reset_token_hash' => $this->token]);
					$token_object->delete($_token['id']);
				} else {
					return Validation::PASSWORDS_NOT_MATCHING;
				}
			}
		}
	}



}