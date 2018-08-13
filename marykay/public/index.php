<?php
session_start();
if (isset($_SESSION['USER'])){
    if ($_SESSION['LOGGED']) $_SESSION['SINCE'] = time();
} else {
    $_SESSION = [
        'USER' => "||GUEST||", 'ID' => 0, 'LOGGED' => false, 'SINCE' => 0, 'ATTEMPT' => 0];
}
/**
 * Front controller
 *
 * PHP version 7.2
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('/', ['controller' => 'Home', 'action' => 'index']);
$router->add('user/login', ['controller' => 'UserController', 'action' => 'login']);
$router->add('user/signup', ['controller' => 'UserController', 'action' => 'signup']);
$router->add('user/logout', ['controller' => 'UserController', 'action' => 'logout']);
$router->add('user/userFound', ['controller' => 'UserController', 'action' => 'userFound']);
$router->add('user/policy', ['controller' => 'UserController', 'action' => 'policy']);
$router->add('user/profile', ['controller' => 'UserController', 'action' => 'view']);
$router->add('user/update', ['controller' => 'UserController', 'action' => 'update']);
$router->add('user/citylist', ['controller' => 'UserController', 'action' => 'citylist']);
$router->add('item/update', ['controller' => 'ItemController', 'action' => 'update']);
$router->add('item/handle', ['controller' => 'ItemController', 'action' => 'handle']);
$router->add('item/edit', ['controller' => 'ItemController', 'action' => 'edit']);
$router->add('item/delete', ['controller' => 'ItemController', 'action' => 'delete']);
$router->add('purchase/cart', ['controller' => 'PurchaseController', 'action' => 'cart']);
$router->add('purchase/towish', ['controller' => 'PurchaseController', 'action' => 'towish']);
$router->add('purchase/disposeof', ['controller' => 'PurchaseController', 'action' => 'disposeof']);
$router->add('purchase/wish', ['controller' => 'PurchaseController', 'action' => 'wish']);
$router->add('purchase/execute', ['controller' => 'PurchaseController', 'action' => 'execute']);
$router->add('/document/pay', ['controller' => 'DocumentController', 'action' => 'purchase']);
$router->add('/document/join', ['controller' => 'DocumentController', 'action' => 'menber']);

$router->add('{controller}/{action}');

$router->dispatch($_SERVER['QUERY_STRING']);
