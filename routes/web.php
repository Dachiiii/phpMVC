<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Middlewares\Middleware;

/*
*
* For Example:
*		['GET', '/', [HomeController::class,'index'], [Middleware::AUTH]];
*/

return [
	['GET', '/', [HomeController::class,'index'], [Middleware::EVERYONE]],
	['GET','/users/signup', [UserController::class,'register'],[Middleware::ANONYMOUS]],
	['POST','/users/signup', [UserController::class,'handleRegister'],[Middleware::ANONYMOUS]],
	['GET','/users/login', [UserController::class,'login'],[Middleware::ANONYMOUS]],
	['POST','/users/login', [UserController::class,'handlelogin'],[Middleware::ANONYMOUS]],
	['GET','/users/logout', [UserController::class,'logout'],[Middleware::AUTH]],
	['POST','/users/logout', [UserController::class,'logoutHandle'],[Middleware::AUTH]],

];