<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/setup.php';

use Kourtis\Controllers;
use Kourtis\Router;

$router = new Router\Router();

$router->get('/', 'MainController', 'index');
$router->get('/contact', 'MainController', 'contact');
$router->get('/single_post', 'PostsController', 'single_post');
$router->get('/post_list', 'PostsController', 'showAllPosts');
$router->get('/testDB', 'MainController', 'test');

$router->post('/contact', 'MainController', 'postContactDetails');

////See inside $router
//echo "<pre>";
//print_r($router);

$router->submit();

