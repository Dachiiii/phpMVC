<?php

namespace App\Models;
use App\Http\Model;
use App\Http\Validation;
use App\Http\Request;
use App\Http\Cookie;
use App\Http\Middlewares\Middleware;

class User extends Model {
	protected string $table = 'users';
	protected const string REGISTRATION_USERNAME_FIELD = 'username';
	protected const string REGISTRATION_PASSWORD1_FIELD = 'password1';
	protected const string REGISTRATION_PASSWORD2_FIELD = 'password2';
	protected const string REMEMBER_ME = 'rememberMe';
	private const string PASSWORD = 'password';
	private const string LOGIN_USING = self::REGISTRATION_USERNAME_FIELD;
	private const array FIELD_PASSWORD = [self::REGISTRATION_PASSWORD1_FIELD => self::PASSWORD];
	private const int PASSWORD_MAX_LENGTH = 25;
	private const int PASSWORD_MIN_LENGTH = 8;

	public static function fillable() {
		return [
			self::REGISTRATION_USERNAME_FIELD,
			self::REGISTRATION_PASSWORD1_FIELD, 
			self::REGISTRATION_PASSWORD2_FIELD
		];
	}

	private array $valid_table_fields = [
		self::REGISTRATION_USERNAME_FIELD,
		self::FIELD_PASSWORD[self::REGISTRATION_PASSWORD1_FIELD]
	];

	public static function field($field) {
		return constant('self::'.$field);
	}

	private function unique_fields() {
		return [self::REGISTRATION_USERNAME_FIELD];
	}
	
	private function rules(array $fields) {
		$errors = [];
		foreach ($fields as $key => $value) {
			if (in_array($key,$this->unique_fields())) {
				$in_DB = $this->where([$key => $value]);
				if (!empty($in_DB)) {
					$errors[] = "{$key} Already Exists";
				}
			}
		}
		return $errors;
	}

	public function register(array $input) {
		$inputs = [];
		$data = $this->validate($input);
		if (count($data) === count(User::fillable())) {
			if (Validation::match($input[self::REGISTRATION_PASSWORD1_FIELD],$input[self::REGISTRATION_PASSWORD2_FIELD])){
				foreach($data as $i) {
					if (in_array($i, array_keys(self::FIELD_PASSWORD)) && (!in_array($i, array_keys($inputs)))){
						$inputs[self::FIELD_PASSWORD[$i]] = $input[$i];
					} else if (in_array($i, $this->valid_table_fields)) {
						$inputs[$i] = $input[$i];
					}
				}
				$inputs[self::PASSWORD] = password_hash($inputs[self::PASSWORD], PASSWORD_BCRYPT);
				$result = $this->rules($inputs);

				if (empty($result)) {
					$new_user = $this->create($inputs);
					$this->save($inputs);
					return 1;
				} else {
					return $result;
				}
			} else {
				return [Validation::PASSWORDS_NOT_MATCHING];
			}
		}
	}

	public function login(string $login, string $password, string|bool $rememberMe = false) {
		$user = $this->get([self::LOGIN_USING => $login]);
		if ($user) {
			session_regenerate_id();
			$verifed = password_verify($password, $user[self::PASSWORD]);
			if ($verifed) {
				if ($rememberMe) {
					$token = new Token();
					$token->remember_me($user['id']);
				}
				$this->save($user);
				return 1;
			} else {
				return [Validation::INCORRECT_PASSWORD];
			}
		} else {
			return ['User does not exists'];
		}
		return false;
	}

	public static function logout() {
		if (Request::user() != Middleware::ANONYMOUS) {
			$user_id = $_SESSION['user_id'];
			unset($_SESSION['loggedin']);
			unset($_SESSION['user_id']);
			session_destroy();
			if (Cookie::get_cookie(Middleware::REMEMBER_ME)) {
				Cookie::unset_cookie(Middleware::REMEMBER_ME);
				Cookie::delete_cookie(Middleware::REMEMBER_ME);
				$user_token = new Token();
				$user_token->delete_user_token($user_id);
			}
			return true;
		}
	}

	private function save(array $user) {
		$_SESSION['loggedin'] = true;
		// $_SESSION[self::LOGIN_USING] = $user[self::LOGIN_USING];
		$_SESSION['user_id'] = $user['id'];
	}

	public function validate(array $fields) {
		$keys = [];
		foreach(array_keys($fields) as $key) {
			if (in_array($key, User::fillable()) && (!in_array($key,$keys))) {
				$keys[] = $key;
			}
		}
		return $keys;
	}
}
