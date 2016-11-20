<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/setup.php';

require_once __DIR__ . '/../app/recaptchalib.php';

use Dotenv\Dotenv;
use Nourhan\Controllers;
use Nourhan\Router;

//Load .env
$dotenv = new Dotenv($path = __DIR__ . '/..');
if (file_exists($path . '/.env'))
{
    $dotenv->load();
    $dotenv->required(
        [
            'CLEARDB_DATABASE_URL',
        ]
    )->notEmpty();
}

$router = new Router\Router();

/** PUBLIC **/
$router->get('/', 'MainController', 'index');
$router->get('/test', 'MainController', 'test');
$router->get('/login', 'MainController', 'login');
$router->get('/calendar', 'MainController', 'calendar');
$router->get('/fitnessProgram', 'MainController', 'fitnessProgram');
$router->get('/realtimelogs', 'MainController', 'realtimeLogs');

/** USER **/
$router->get('/profile', 'MainController', 'profileStats');
$router->post('/profile', 'MainController', 'userLogin');
$router->get('/logout', 'MainController', 'logout');
$router->get('/register', 'MainController', 'register');
$router->post('/registerclass', 'MainController', 'registerClass');
$router->post('/unregisterclass', 'MainController', 'unregisterClass');
$router->get('/signin', 'MainController', 'signin');
$router->get('/requestProgram', 'UserController', 'requestProgram');
$router->post('/submitProgram', 'UserController', 'submitProgram');


/** ADMIN **/
//CLASSES SCHEDULE
$router->get('/editschedule', 'AdminController', 'editSchedule');
$router->get('/editclass', 'AdminController', 'editClass');
$router->post('/addclass', 'AdminController', 'addClass');
$router->post('/deleteclass', 'AdminController', 'deleteClass');
$router->post('/updateclass', 'AdminController', 'updateClass');
$router->get('/adminsearchclasses', 'AdminController', 'searchClasses');

//CLASS REGISTRATIONS
$router->get('/registrations', 'AdminController', 'registrations');
$router->get('/searchregistrations', 'AdminController', 'searchRegistrations');

//Users
$router->get('/editusers', 'AdminController', 'editUsers');
$router->get('/adminsearchusers', 'AdminController', 'searchUsers');
$router->post('/updateuser', 'AdminController', 'updateUser');
$router->post('/deleteuser', 'AdminController', 'deleteUser');
$router->post('/add_multiple_users', 'AdminController', 'addMultipleUsers');
$router->post('/add_user', 'AdminController', 'addUser');

//STATS
$router->get('/adminvisitstats', 'AdminController', 'stats');
$router->post('/adminstatsyear', 'AdminController', 'postStatsYear');
$router->post('/adminstatsmonth', 'AdminController', 'postStatsMonth');
$router->post('/adminstatsday', 'AdminController', 'postStatsDay');
$router->post('/adminstatshour', 'AdminController', 'postStatsHour');

//Logs
$router->get('/adminstatsuser', 'MainController', 'userStats');
$router->get('/logs', 'MainController', 'usersLogs');
$router->get('/adminlogssearchclasses', 'MainController', 'searchLogs');
$router->get('/adminsearchrealtime', 'MainController', 'searchRealtimeLogs');

/** Nurse **/
$router->get('/nurse-pending', 'NurseController', 'seePendingCertificates');
$router->get('/nurse-approved', 'NurseController', 'seeApprovedCertificates');
$router->get('/nurse-rejected', 'NurseController', 'seeRejectedCertificates');
$router->post('/approveCertificate', 'NurseController', 'approveCertificate');
$router->post('/rejectCertificate', 'NurseController', 'rejectCertificate');

/** Trainer **/
$router->get('/programRequests', 'TrainerController', 'programRequests');
$router->post('/trainerResponse', 'TrainerController', 'trainerResponse');



////See inside $router
//echo "<pre>";
//print_r($router);

$router->submit();


