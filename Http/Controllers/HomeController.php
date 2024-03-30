<?php

namespace App\Http\Controllers;
use App\Http\Response;
use App\Http\Request;

class HomeController {

	public function index(): Response {
		return new Response('index');
	}

}