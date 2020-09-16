<?php

require '../vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['db']['host']   = "127.0.0.1";
$config['db']['user']   = "root";
$config['db']['pass']   = "12345";
$config['db']['dbname'] = "laris";

$app = new \Slim\App(["settings" => $config]);

$container = $app->getContainer();

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    try{
        $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
            $db['user'], $db['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }
    catch(PDOException $ex){
        die($ex->getMessage());

    }
};

$container['ResponseProvider'] = function() {
    return new \App\Utilities\ResponseProvider();
};
$container['\UserController'] = function($app) {
    return new App\Controllers\UserController($app);
};
$container['\DeviceController'] = function($app) {
    return new App\Controllers\DeviceController($app);
};
$container['\RoomController'] = function($app) {
    return new App\Controllers\RoomController($app);
};
$container['\TokenController'] = function($app) {
    return new App\Controllers\TokenController($app);
};
$container['\ServerController'] = function($app) {
    return new App\Controllers\ServerController($app);
};

$app->add(function ($request, $response, $next) {
    if($request->isPost()) {
        if (strpos($request->getContentType(), "application/json"))
            die("To nie json " . $request->getContentType());
    }
    return $next($request, $response);
});

require_once 'routes/ServerRoutes.php';
require_once 'routes/UserRoutes.php';
require_once 'routes/RoomRoutes.php';
require_once 'routes/DeviceRoutes.php';
require_once 'routes/TokenRoutes.php';

$app->run();