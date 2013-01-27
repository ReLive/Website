<?php
/*
 * Include and prepare necessary stuff
 */
use Kunststube\Router\Router, Kunststube\Router\Route;
require_once 'vendor/php-activerecord/php-activerecord/ActiveRecord.php';
require_once 'vendor/kunststube/router/Kunststube/Router/Router.php';
require_once 'vendor/autoload.php';
date_default_timezone_set('Europe/Berlin');
$router = new Router;
$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader);
$twig_host = new Twig_SimpleFilter('host', function($arg){
	return parse_url($arg, PHP_URL_HOST);
});
$twig_router = new Twig_SimpleFunction('url_rt', function($arg){
	global $router;
	return $router->reverseRoute($arg);
});
$twig_cur_url = new Twig_SimpleFunction('url_cur', function(){
 	$pageURL = 'http';
 	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 	$pageURL .= "://";
 	if ($_SERVER["SERVER_PORT"] != "80") {
  	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 	} else {
  	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
});
$twig->addFunction($twig_router);
$twig->addFunction($twig_cur_url);
$twig->addFilter($twig_host);
/*
 * Config
 */

// Database
ActiveRecord\Config::initialize(function($cfg)
{
	$cfg->set_model_directory('models');
    $cfg->set_connections(array(
        'production' => 'mysql://user:pass@mysql.host.com/database;charset=utf8'
        ));
    $cfg->set_default_connection('production');
});

// Routes
$router->add('/', array('controller' => 'main', 'action' => 'index'));
$router->add('/:podcast', array('controller' => 'podcast', 'action' => 'index'));
$router->add('/:podcast/\d+:id', array('controller' => 'podcast', 'action' => 'episode'));

// Dispatch function
$router->defaultCallback(function (Route $route) {
	global $twig, $router;
	$path = 'controller/' . $route->controller . '.php';
	if (file_exists($path)){
		include($path);
	}
});

// Error 404
$router->route($_GET['url'], function ($url) {
	global $twig;
	echo $twig->render('error.html', array(
			'error_title'		=> 'Ugh… Fehler 404!',
			'error_description'	=> 'Huch? Wo bist du denn gelandet?'));
	die();
});
?>