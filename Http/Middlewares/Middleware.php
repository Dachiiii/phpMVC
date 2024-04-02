<?php

namespace App\Http\Middlewares;

class Middleware {
	public const string ANONYMOUS = 'anonymous';
	public const string EVERYONE = 'everyone';
	public const string ADMINISTRATOR = 'administrator';
	public const string REMEMBER_ME = 'remember_me';
	public const string ACTIVE_USER = "active_user";
	public const string AUTH = 'auth';
	public const string NOTHING = "";
	public const int NOT_FOUND = 404;

	const MAP = [
		self::ANONYMOUS => Anonymous::class,
		self::AUTH => Auth::class,
		self::ACTIVE_USER => ActiveUser::class,
		self::EVERYONE => true,
		self::ADMINISTRATOR => Administrator::class,
	];

	const middlewares = [
		// RememberMe::class,	/* Uncomment this when DB and tables are setup. */
	];
}