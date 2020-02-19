<?php

require_once 'Config.php';
require_once 'vendor/autoload.php';

$databaseConfiguration = new App\Core\DatabaseConfiguration(
    Config::DATABASE_HOST,
    Config::DATABASE_USER,
    Config::DATABASE_PASS,
    Config::DATABASE_NAME
);

$databaseConnection = new App\Core\DatabaseConnection($databaseConfiguration);

$url = strval(filter_input(INPUT_GET, 'URL'));
$httpMethod = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
$router = new App\Core\Router();
$routes = require_once 'Routes.php';

foreach($routes as $route){
    $router->add($route);
}

$route=$router->find($httpMethod, $url);
$arguments = $route->extractArguments($url);

$fullControllerName = '\\App\\Controllers\\'. $route->getControllerName() . 'Controller';
$controller = new $fullControllerName($databaseConnection);

$fingerprintProviderFactoryClass = Config::FINGERPRINT_PROVIDER_FACTORY;
$fingerprintProviderFactoryMethod = Config::FINGERPRINT_PROVIDER_METHOD;
$fingerprintProviderFactoryArgs = Config::FINGERPRINT_PROVIDER_ARGS;
$fingerprintProviderFactory = new $fingerprintProviderFactoryClass;
$fingerprintProvider = $fingerprintProviderFactory->$fingerprintProviderFactoryMethod(...$fingerprintProviderFactoryArgs);


$sessionStorageClassName = Config::SESSION_STORAGE;
$sessionStorageConstructorArguments = Config::SESSION_STORAGE_DATA;
$sessionStorage = new $sessionStorageClassName(...$sessionStorageConstructorArguments);

$session = new \App\Core\Session\Session($sessionStorage, Config::SESSION_LIFETIME);
$session->setFingerprintProvider($fingerprintProvider);

$controller->setSession($session);
$controller->getSession()->reload();
$controller->__pre();
call_user_func_array([$controller, $route->getMethodName()], $arguments);
$controller->__post();
$controller->getSession()->save();

$data = $controller->getData();
$breadcrums = $controller->getBreadcrums();

if (count($breadcrums)){
    $breadcrums[count($breadcrums)-1]->last = true;
}

$data['breadcrums'] = $breadcrums;

#twig file system loader
$loader = new \Twig_Loader_Filesystem("./views");
$twig = new \Twig_Environment($loader, [
    "cashe" => "./twig-cashe",
    "auto_reload" => true
]); //render

echo $twig -> render(
    $route->getControllerName(). '/' . $route->getMethodName() . '.html',
    $data 
);



