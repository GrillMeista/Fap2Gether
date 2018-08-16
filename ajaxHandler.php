<?php
/*
 * This file should work as an interface between AJAX and PHP-Objects
 * You can call Methods, send and receive data
 * GET and POST are supported
 *
 * ajaxHandler.php :
 *      to send data you need to set the url like this : ajaxHandler.php/service/method?optionalParameter=5
 *      service : defines the addressed class
 *      method  : defines the addressed object-method to communicate with
 *
 * To address your Classes, you need a ajax_config.php :
 *      return [
 *               'Service' => [
 *                    'anyName' => 'methodName',
 *                    'anyName' => 'methodName',
 *                    'intFunc'  => 'getInteger',
 *               ]
 *             ];
 * ---------------------------------------------------------------
 *      Call method 'getInteger' from your class 'Service' like this :
 *      ajaxHandler.php/Service/intFunc
 * ---------------------------------------------------------------
 */

include_once 'Modules/Application/Autoloader/Autoloader.php';
Autoloader::register();

$config = include_once 'config/ajax_config.php';

$data = getNames($config);

//Autoloader or this
//include_once($data['service'] . '.php');

if(!isset($config[$data['service']][$data['method']])){
    echo "Method or Service not found!";
    die();
}

echo call_user_func_array(
    [
        (new $data['service']),
        $config[$data['service']][$data['method']]
    ],
    ($_SERVER['REQUEST_METHOD'] === 'GET') ? input($_GET) : input($_POST)
);

function input($input)
{
    foreach ($input as $key => $value){
        $input[$key] = trim(strip_tags(stripslashes(htmlspecialchars($value))));
    }

    return $input;
}

function getNames($config)
{
    $response = new Response();

    $service = explode('/', $_SERVER['PATH_INFO']);

    if(!isset($config[$service[1]])) {
        $response->setStatusCode(Response::NOT_FOUND);
        $response->setMessage("Service not found!");
        echo $response;
        die();
    }

    if(isset($config[$service[1]]) && !isset($config[$service[1]][$service[2]])) {
        $response->setStatusCode(Response::NOT_FOUND);
        $response->setMessage("No Method to Service found!");
        echo $response;
        die();
    }

    if(!isset($config[$service[1]][$service[2]])) {
        $response->setStatusCode(Response::NOT_FOUND);
        $response->setMessage("Method or Service not found!");
        echo $response;
        die();
    }

    return [
        'service' => $service[1],
        'method'  => $service[2],
    ];
}