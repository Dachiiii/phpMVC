<?php

namespace App\Http;
use function FastRoute\simpleDispatcher;
use FastRoute\RouteCollector;
use App\Http\Middlewares\Middleware;

class Kernel {

	private array $route = [];

	public function handle(Request $request): Response {

		$dispatcher = simpleDispatcher(function(RouteCollector $routeCollector) {
			
			$routes = include BASE_PATH . '/routes/web.php';

			$this->route = $routes;

			foreach($routes as $route) {

				$routeCollector->addRoute(...$route);
			
			}
		});

		$routeInfo = $dispatcher->dispatch($request->method(),$request->path());
		if (count($routeInfo) != 3){
			return new Response('404',[],404);
		}

		[$status, [$controller, $method], $vars] = $routeInfo;

		foreach ($this->middleware($request,$vars) as $middleware) {
			$ware = Middleware::MAP[$middleware];
			if (!is_bool($ware)) {
				(new $ware)->handle($vars);
			}
		}
		foreach(Middleware::middlewares as $middleware) {
			(new $middleware)->handle();
		}
		$response = call_user_func_array([new $controller,$method], $vars);
		return $response;
	}


	public function middleware(Request $request, array $vars) {
		foreach ($this->route as $route) {	
			$routePath = explode('/',$route[1]);
			
			foreach($vars as $key => $value) {
				$route[1] = str_replace("{{$key}}", $value, $route[1]);
			}
			if ($request->method() == $route[0] && $request->path() == $route[1]) {
				return $route[3] ?? Middleware::EVERYONE;
			}
		}
	}
}