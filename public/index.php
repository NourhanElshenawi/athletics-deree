<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/setup.php';

use Nourhan\Controllers;
use Nourhan\Router;

$router = new Router\Router();




//$router->get('/', 'MainController', 'home');
$router->get('/test', 'MainController', 'index');
$router->get('/', 'MainController', 'test');
//$router->get('/admin', 'MainController', 'admin');
//$router->get('/admin2', 'MainController', 'admin2');
//$router->post('/admin2', 'MainController', 'admin2');
//$router->get('/car', 'MainController', 'car');
//$router->post('/car', 'MainController', 'car');

//$router->post('/admin', 'MainController', 'upload');

//$router->get('/test', 'MainController', 'includeInCarousel');
//$router->get('/remove', 'MainController', 'removeFromCarousel');
//$router->get('/delete', 'MainController', 'deleteFromCarousel');

////See inside $router
//echo "<pre>";
//print_r($router);

$router->submit();


