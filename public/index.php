<?php

// error_reporting(0);

require_once dirname(__DIR__) . '/vendor/autoload.php';

session_start();
use App\Http\Request;
use App\Http\Response;
use App\Http\Kernel;
use Dotenv\Dotenv;

define('BASE_PATH',dirname(__DIR__));

$env = Dotenv::createImmutable(BASE_PATH);
$env->load();

$request = Request::createFromGlobals();

$kernel = new Kernel();

$response = $kernel->handle($request);

$response->send();