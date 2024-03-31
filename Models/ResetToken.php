<?php

namespace App\Models;
use App\Http\Model;
use App\Models\User;

class ResetToken extends Model {
	protected string $table = 'user_reset_tokens';
	private string $FIELD_NAME_USER_ID = "user_id";
	private string $FIELD_NAME_USER_EMAIL = "user_email";
	private string $FIELD_NAME_RESET_TOKEN_HASH = "reset_token_hash";
	private string $FIELD_NAME_TOKEN_EXPIRES_AT = "reset_token_expires_at";

	public function generate_reset_token(int $user_id, string $user_email, string $reset_token_hash, string $reset_token_expires_at) {
		$token = [
			$this->FIELD_NAME_USER_ID => $user_id,
			$this->FIELD_NAME_USER_EMAIL => $user_email,
			$this->FIELD_NAME_RESET_TOKEN_HASH => $reset_token_hash,
			$this->FIELD_NAME_TOKEN_EXPIRES_AT => $reset_token_expires_at,
		];
		$this->create($token);
	}
	public function get_user_by_reset_token(string $token): array|bool {
		$token = $this->get([$this->FIELD_NAME_RESET_TOKEN_HASH => $token]);
		if ($token && !$this->token_expired($token)) {
			$userObject = new User();
			return $userObject->get(['id' => $token['user_id'], 'email' => $token['user_email']]);
		} return false;
	}

	private function token_expired($token) {
		if (strtotime($token[$this->FIELD_NAME_TOKEN_EXPIRES_AT]) <= time()) {
			$this->delete($token['id']);
			return true;
		} return false;
	}
}