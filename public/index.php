<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/setup.php';

require_once __DIR__ . '/../app/recaptchalib.php';

use Nourhan\Controllers;
use Nourhan\Router;

$router = new Router\Router();


/** PUBLIC **/
$router->get('/', 'MainController', 'index');
$router->get('/test', 'MainController', 'test');
$router->get('/login', 'MainController', 'login');
$router->get('/calendar', 'MainController', 'calendar');
$router->get('/fitnessProgram', 'MainController', 'fitnessProgram');

/** USER **/
$router->get('/profile', 'MainController', 'profileStats');
$router->post('/profile', 'MainController', 'userLogin');
$router->get('/logout', 'MainController', 'logout');
$router->get('/register', 'MainController', 'register');
$router->post('/registerclass', 'MainController', 'registerClass');
$router->post('/unregisterclass', 'MainController', 'unregisterClass');
$router->get('/signin', 'MainController', 'signin');
$router->get('/realtimelogs', 'MainController', 'realtimeLogs');
$router->get('/requestProgram', 'UserController', 'requestProgram');
$router->post('/submitProgram', 'UserController', 'submitProgram');

/** ADMIN **/
//CLASSES SCHEDULE
$router->get('/editschedule', 'MainController', 'editSchedule');
$router->get('/editclass', 'MainController', 'editClass');
$router->post('/addclass', 'MainController', 'addClass');
$router->post('/deleteclass', 'MainController', 'deleteClass');
$router->post('/updateclass', 'MainController', 'updateClass');
$router->get('/adminsearchclasses', 'MainController', 'searchClasses');

/** Trainer **/
$router->get('/programRequests', 'TrainerController', 'programRequests');
$router->post('/trainerResponse', 'TrainerController', 'trainerResponse');



//CLASS REGISTRATIONS
$router->get('/registrations', 'MainController', 'registrations');
$router->get('/searchregistrations', 'MainController', 'searchRegistrations');

//Users
$router->get('/editusers', 'MainController', 'editUsers');
$router->get('/adminsearchusers', 'MainController', 'searchUsers');
$router->post('/updateuser', 'MainController', 'updateUser');
$router->post('/deleteuser', 'MainController', 'deleteUser');
$router->post('/add_multiple_users', 'MainController', 'addMultipleUsers');
$router->post('/add_user', 'MainController', 'addUser');

//STATS
$router->get('/adminvisitstats', 'MainController', 'stats');
$router->get('/adminstatsuser', 'MainController', 'userStats');
$router->get('/logs', 'MainController', 'usersLogs');
$router->post('/adminstatsyear', 'MainController', 'postStatsYear');
$router->post('/adminstatsmonth', 'MainController', 'postStatsMonth');
$router->post('/adminstatsday', 'MainController', 'postStatsDay');
$router->post('/adminstatshour', 'MainController', 'postStatsHour');

//Logs
$router->get('/adminlogssearchclasses', 'MainController', 'searchLogs');
$router->get('/adminsearchrealtime', 'MainController', 'searchRealtimeLogs');

/** Nurse **/
$router->get('/nurse-pending', 'NurseController', 'seePendingCertificates');
$router->get('/nurse-approved', 'NurseController', 'seeApprovedCertificates');
$router->get('/nurse-rejected', 'NurseController', 'seeRejectedCertificates');
$router->post('/approveCertificate', 'NurseController', 'approveCertificate');


////See inside $router
//echo "<pre>";
//print_r($router);

$router->submit();


