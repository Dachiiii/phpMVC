<?php

namespace App\Http\Auth;
use App\Models\ResetToken;
use App\Models\User;
use App\Http\Request;

class ForgotPassword {
	private const string HASH = "sha256";
	private string $valid_input_email_name = 'email';
	private array $input;
	private string $user_email;
	private string $user_id;
	private string $token;

	public function __construct(array $input) {
		$this->input = $input;
	}

	public function generate_user_reset_token() {
		$token = bin2hex(random_bytes(16));
		$token_hash = hash(self::HASH,$token);
		$expiry = date("Y-m-d H:i:s",time() + 60 * 30);
		if ($this->is_valid_email_input_name() && $this->user_found_with_this_email()) {
			$_token = new ResetToken();
			$_token->generate_reset_token($this->user_id, $this->user_email, $token_hash, $expiry);
			$this->token = $token_hash;
			return true;
		}
	}

	private function is_valid_email_input_name(): bool {
		if (in_array($this->valid_input_email_name, array_keys($this->input))) {
			$this->user_email = $this->input[$this->valid_input_email_name];
			return true;
		} return false;
	}

	private function user_found_with_this_email(): array|bool {
		$object = new User();
		$user = $object->get([$this->valid_input_email_name => $this->user_email]);
		if (is_array($user)) {
			$this->user_id = $user['id'];
		}
		return $user;
	}
	public function get_email() {
		return $this->user_email;
	}

	public function generate_token_url() {
		return Request::url().Request::request_uri().'/'.$this->token;
	}
}