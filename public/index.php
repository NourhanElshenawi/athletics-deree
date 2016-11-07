<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/setup.php';

require_once __DIR__ . '/../app/recaptchalib.php';



use Nourhan\Controllers;
use Nourhan\Router;

$router = new Router\Router();



$router->get('/', 'MainController', 'index');
$router->get('/test', 'MainController', 'test');
$router->get('/login', 'MainController', 'login');

$router->get('/calendar', 'MainController', 'calendar');
$router->get('/fitnessProgram', 'MainController', 'fitnessProgram');
//$router->get('/profile', 'MainController', 'profile');

/******USER****/
$router->post('/profile', 'MainController', 'userLogin');
$router->get('/logout', 'MainController', 'logout');
$router->get('/register', 'MainController', 'register');
$router->post('/unregisterclass', 'MainController', 'unregisterClass');
$router->post('/registerclass', 'MainController', 'registerClass');
$router->get('/signin', 'MainController', 'signin');
$router->get('/realtimelogs', 'MainController', 'realtimeLogs');
//$router->get('/friendslogs', 'MainController', 'friendslogs');

/********ADMIN**********/
///////CLASSES SCHEDULE
$router->get('/editschedule', 'MainController', 'editSchedule');
$router->get('/editclass', 'MainController', 'editClass');
$router->post('/addclass', 'MainController', 'addClass');
$router->post('/deleteclass', 'MainController', 'deleteClass');
$router->post('/updateclass', 'MainController', 'updateClass');
$router->get('/adminsearchclasses', 'MainController', 'searchClasses');

///////CLASS REGISTRATIONS
$router->get('/registrations', 'MainController', 'registrations');
$router->get('/searchregistrations', 'MainController', 'searchRegistrations');
////////Users
$router->get('/editusers', 'MainController', 'editUsers');
$router->get('/adminsearchusers', 'MainController', 'searchUsers');
$router->post('/updateuser', 'MainController', 'updateUser');
$router->post('/deleteuser', 'MainController', 'deleteUser');
$router->post('/add_multiple_users', 'MainController', 'addMultipleUsers');
$router->post('/add_user', 'MainController', 'addUser');

/////STATS
$router->get('/adminstatsmonth', 'MainController', 'statsMonth');
$router->get('/adminstatsuser', 'MainController', 'userStats');
$router->get('/logs', 'MainController', 'usersLogs');
$router->post('/adminstatsmonth', 'MainController', 'postStatsMonth');
//////Logs
$router->get('/adminlogssearchclasses', 'MainController', 'searchLogs');
$router->get('/adminsearchrealtime', 'MainController', 'searchRealtimeLogs');

$router->get('/profile', 'MainController', 'profileStats');

////See inside $router
//echo "<pre>";
//print_r($router);

$router->submit();


