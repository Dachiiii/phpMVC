<?php

namespace App\Http\Controllers;
use App\Http\Response;
use App\Http\Request;
use App\Models\User;
use App\Http\Auth\ForgotPassword;
use App\Http\Auth\ChangePassword;
use App\Models\ResetToken;
use App\Http\Auth\Mail;

class UserController {

	public function register(): Response {
		return new Response('users/register');
	}

	public function handleRegister(): Response {
		$post = Request::_post();
		$object = new User();
		$user = $object->register($post);

		if ($user != 1) {
			$context = ['errors' => $user];
			return new Response('users/register',$context);
		}
		Response::redirect();
		return new Response('index');
	}

	public function login(): Response {
		return new Response('users/login');
	}
	public function handleLogin(): Response {
		$post = Request::_post();
		$username = $post['username'];
		$password = $post['password'];
		$object = new User();
		$user = $object->login($username,$password,(isset($post['remember_me']) ? $post['remember_me'] : false));
		if ($user != 1) {
			$context = ['errors' => $user];
			return new Response('users/login',$context);
		}
		Response::redirect();
		return new Response('index');
	}

	public function logout(): Response {
		return new Response('users/logout');
	}

	public function logoutHandle(): Response {
		$user = new User();
		$user->logout();
		Response::redirect('users/login');
		return new Response('users/login');
	}

	public function forgotPassword(): Response {
		return new Response('users/password_recovery/forgot-password');
	}
	public function forgotPasswordReset(): Response {
		$forgot = new ForgotPassword(Request::_post());
		if ($forgot->generate_user_reset_token()) {
			$email = new Mail();
			$mail = $email->setup(true);
			$mail->addAddress($forgot->get_email());
			$mail->Subject = $email::PASSWORD_RESET_EMAIL_SUBJECT;
			$url = $forgot->generate_token_url();
			$mail->Body = "Click <a href='$url'>here</a> to recover your password.";
			$email->sendMail($mail);
		};
		return new Response('users/password_recovery/submited-password-recovery');
	}

	public function recoverPassword(string $token): Response {
		$token_object = new ResetToken();
		if (!$token_object->get_user_by_reset_token($token)) {
			Response::status_code(404);
			return new Response('404');
		} else {
			return new Response('users/password_recovery/recover-password');
		}
	}
	public function recoverPasswordSave(string $token): Response {
		$changePassword = new ChangePassword($token);
		$changePassword->change(Request::_post());
		return new Response('users/password_recovery/password_updated_successfully');
	}
}