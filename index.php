<?php
// ini_set('display_errors', 1);
require_once 'core/model.php';
require_once 'core/controller.php';

class Route
{
	static function start()
	{
		$controller_name = 'info';
		$action_name = 'index';
		
		$tmp = explode('?', $_SERVER['REQUEST_URI']);
		$routes = explode('/', $tmp[0]);

		$n = 1;

		if ( !empty($routes[$n]) )
		{	
			$controller_name = $routes[$n];
		}

		if ( !empty($routes[$n+1]) )
		{
			$action_name = $routes[$n+1];
		}

		if ( !empty($routes[$n+2]) )
		{
			$parameters = $routes[$n+2];
		}

		$model_name = 'Model_'.$controller_name;
		$controller_name = 'Controller_'.$controller_name;
		$action_name = 'action_'.$action_name;

		$model_file = strtolower($model_name).'.php';
		$model_path = __DIR__ . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . $model_file;

		if(file_exists($model_path))
		{
			require $model_path;
		}

		$controller_file = strtolower($controller_name).'.php';
		$controller_path = __DIR__ . DIRECTORY_SEPARATOR . "controllers" . DIRECTORY_SEPARATOR . $controller_file;

		if(file_exists($controller_path))
		{
			require $controller_path;
		}
		else
		{
			Route::ErrorPage404();
		}

		$controller = new $controller_name();
		$action = $action_name;
		
		if(method_exists($controller, $action))
		{
			$controller->$action($parameters);
		}
		else
		{
			Route::ErrorPage404();
		}
	}
	
	function ErrorPage404()
	{
		$host = 'http://'.$_SERVER['HTTP_HOST'].'/';
		header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		header('Location:'.$host.'404');
    }
}

Route::start();
?>