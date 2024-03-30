<?php

namespace App\Http\Controllers;
use App\Http\Response;
use App\Http\Request;
use App\Models\User;

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
}