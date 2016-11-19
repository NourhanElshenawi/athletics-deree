<?php
namespace Nourhan\Controllers;

use Nourhan\Database\DB;
use Nourhan\Services\Upload;
use Nourhan\ReCaptcha;
use Nourhan\Services\ChangeCarousel;


class MainController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }
/*****HOME*****/
    public function index()
    {
        echo $this->twig->render('index.twig');
    }

    public function test()
    {
        echo $this->twig->render('master2.twig');
    }

    public function calendar()
    {
        echo $this->twig->render('calendar.twig');
    }

    public function fitnessProgram()
    {
        $db = new DB();
        $classes = $db->getClasses();

        $classes = $this->beautifyClassesForCalendar($classes);

        echo $this->twig->render('fitnessProgram.twig', array('classes'=> $classes));
    }

    public function beautifyClassesForCalendar($classes) {
        foreach ($classes as $key=>$class) {
//            var_dump($class);

            $class['days']=array();

            if($class['monday']){
                $classes[$key]['days'][]="1";
//                $classes[$key]['days'][]="monday";
//                echo $class['monday'];
            }
            if($class['tuesday']){
                $classes[$key]['days'][]="2";
//                $classes[$key]['days'][]="tuesday";
            }
            if($class['wednesday']){
                $classes[$key]['days'][]="3";
//                $classes[$key]['days'][]="wednesday";
            }
            if($class['thursday']){
                $classes[$key]['days'][]="4";
//                $classes[$key]['days'][]="thursday";
            }
            if($class['friday']){
                $classes[$key]['days'][]="5";
//                $classes[$key]['days'][]="friday";
            }
        }

        return $classes;
    }
/*****CUSTOMER*****/
    public function profileStats()
    {

        $db = new DB();
        $logs = $db->getUserLogs($_SESSION['user']['id']);
        foreach ($logs as $log) {
            $date = $log['login'];
            //below i get the year, month and the rest of the DateTime format (day and time)
            $array = explode('-',$date,3);
            //below i add the day to the date array
            $temp = $array[2];
            $array[2] = explode(' ',$array[2],2)[0];
//                    d($array);
            $monthNum  = $array[1];
            $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F'); // March
            $array[1] = $monthName;
//            $dayNum  = $array[1];
//            $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
//            $monthName = $dateObj->format('F'); // March
//            $array[1] = $monthName;

//            d($array);

        }

//        $d(array_count_values($array[]));
        /*  $date = "2016-06-09 00:38:47";
          //below i get the year, month and the rest of the DateTime format (day and time)
  //        $array = explode('-',$date,3);
          //below i add the day to the date array
  //        $temp = $array[2];
  //        $array[2] = explode(' ',$array[2],2)[0];

  //        echo "date </br>";
  //        var_dump($date);
  //        echo "split array </br>";
  //        var_dump($array);
  //        echo "complete </br>";
  //        var_dump($array);
  //        $monthNum  = $array[1];
  //        $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
  //        $monthName = $dateObj->format('F'); // March

  */
//        $array[1] = $monthName;
//        var_dump($array);
        $array = array("January", "January", "January", "February", "February", "March");
        $vals = array_count_values($array);
        for ($x=0; $x<count($array);){

            $x++;
        }

        $classes = $this->getUserClasses($_SESSION['user']['id']);
        $classes = $this->beautifyClassesForCalendar($classes);

        echo $this->twig->render('customer/profile.twig', array('vals'=>$vals, 'classes'=>$classes));
    }

    public function getUserClasses($id) {

        $db = new DB();

        $userRegistrations = $db->getUserRegistrations($id);

        foreach ($userRegistrations as $registration){

            $classes[] = $db->getClass($registration['classID']);

        }

        return $classes;
    }

    public function login()
    {
        echo $this->twig->render('login.twig');
    }

    public function verifyResponse()
    {
// your secret key
        $secret = "6Ld91QgUAAAAAIRUcZnJqHYO836O9dCCesrdMgLg";
// empty response
        $response = null;
// check secret key
        $reCaptcha = new ReCaptcha\ReCaptcha($secret);

// if submitted check response
        if ($_POST["g-recaptcha-response"]) {
            $response = $reCaptcha->verifyResponse(
                $_SERVER["REMOTE_ADDR"],
                $_POST["g-recaptcha-response"]
            );
        }

        if ($response != null && $response->success) {
            //REDIRECT TO PROFILE
//            echo "Hi " . $_POST["username"] . " (" . $_POST["password"] . "), thanks for submitting the form!";
            return true;

        } else {
            return false;
            //REDIRECT TO ERROR'
//            echo "not submitted";
            return false;
        }
    }

    public function userLogin()
    {

        if(isset($_SESSION['user'])){

            header('Location: /profile');
        }
        else {

            if (($this->verifyResponse())) {

                $db = new DB();
                $user = $db->getUser($_POST["username"], $_POST["password"]);

                if (empty($user)) {
                    //404 or something
                    echo "user not found";
                } else {
//                $this->profileStats();
                    $_SESSION['user'] = $user;
//                    echo $this->twig->render('customer/profile.twig');
                    header('Location: /profile');
                }


            }
        }
    }

    public function logout()
    {
        unset($_SESSION['user']);
//        echo $this->twig->render('login.twig');
        header('Location: /login');
    }


    public function profile()
    {
        echo $this->twig->render('customer/profile.twig');
    }

    public function beautifyClasses($classes){

        $db = new DB();

        foreach ($classes as $key=>$class ){

            $temp = ($class['currentCapacity']*100)/$class['capacity'];
            $temp2 = $class['currentCapacity'];

            $classes[$key]['currentCapacityPercentage'] = $temp;
            $classes[$key]['currentCapacity'] = $temp2;

            $classes[$key]['users'] = $db->getRegisteredUsers($class['id']);

            $class['days']=array();

            if($class['monday']){
                $classes[$key]['days']['monday']="1";
//                $classes[$key]['days'][]="monday";
//                echo $class['monday'];
            } else {
                $classes[$key]['days']['monday']="0";
            }
            if($class['tuesday']){
                $classes[$key]['days']['tuesday']="1";
//                $classes[$key]['days'][]="tuesday";
            }else {
                $classes[$key]['days']['tuesday']="0";
            }
            if($class['wednesday']){
                $classes[$key]['days']['wednesday']="1";
//                $classes[$key]['days'][]="wednesday";
            }else {
                $classes[$key]['days']['wednesday']="0";
            }
            if($class['thursday']){
                $classes[$key]['days']['thursday']="1";
//                $classes[$key]['days'][]="thursday";
            }else {
                $classes[$key]['days']['thursday']="0";
            }
            if($class['friday']){
                $classes[$key]['days']['friday']="1";
//                $classes[$key]['days'][]="friday";
            }else {
                $classes[$key]['days']['friday']="0";
            }
        }

        return $classes;
    }

    /***********ADMIN************/

    ////////EDIT CLASS SCHEDULE

    public function editSchedule()
    {
        $db = new DB();
        $classes = $db->getClasses();
        $allInstructor = $db->getInstructors();
        $classes = $this->beautifyClasses($classes);
        $instructors = array();
        foreach ($classes as $class ){
            $id = "".$class["instructorID"]."";
            $instructors[]= $db->getInstructor($id);
    }
        echo $this->twig->render('admin/editSchedule.twig', array('classes'=> $classes, 'instructors'=> $instructors, 'allInstructors'=>$allInstructor));
    }

    public function addClass()
    {

        $db = new DB();
        $test = $db->addClass($_POST['name'], $_POST['duration'], $_POST['instructorID'], $_POST['startTime'],
            $_POST['period'],
            $_POST['capacity'],$_POST['location'],(int)in_array('monday',$_POST['days']),
            (int)in_array('tuesday',$_POST['days']), (int)in_array('wednesday',$_POST['days']),
            (int)in_array('thursday',$_POST['days']), (int)in_array('friday',$_POST['days']));


        if($test){

            header('Location: /editschedule');

        } else{
            //ERROR
        }

    }

    public function searchClasses(){
        $db = new DB();

        $result = $db->searchClasses($_GET['keyword']);

        $allInstructor = $db->getInstructors();

        foreach ($result as $key=>$class ){

            $temp = ($class['currentCapacity']*100)/$class['capacity'];
            $temp2 = $class['currentCapacity'];

            $result[$key]['currentCapacityPercentage'] = $temp;
            $result[$key]['currentCapacity'] = $temp2;
        }


        $instructors = array();

        foreach ($result as $class ){

            $id = "".$class["instructorID"]."";
            $instructors[]= $db->getInstructor($id);

        }

        echo $this->twig->render('admin/editSchedule.twig', array('classes'=> $result, 'instructors'=> $instructors, 'allInstructors'=>$allInstructor));


    }

    public function updateClass()
    {
        $DB = new DB();
        if($DB->updateClass($_POST['id'],$_POST['duration'],$_POST['startTime'],$_POST['capacity'], $_POST['instructor']))
        {
            header('Location: /editschedule');
        }

    }


    public function deleteClass()
    {
        $db = new DB();
        $db->deleteClass($_POST['id']);

    }

    public function registrations()
    {
        $db = new DB();
        $classes = $db->getClasses();

        if(!isset($_GET['class'])){
            echo $this->twig->render('admin/registrations.twig', array('classes'=>$classes));
        }
        else {
            $users = $db->getRegisteredUsers($_GET['class']);
            $class = $db->getClass($_GET['class']);
            echo $this->twig->render('admin/registrations.twig', array('classes'=>$classes, 'users'=>$users, 'class'=>$class));
        }

    }

    public function searchRegistrations()
    {
        $db = new DB();
        $classes = $db->getClasses();

            $users = $db->searchClassRegistrations($_GET['classID'], $_GET['keyword']);
            $class = $db->getClass($_GET['classID']);
        if (empty($users)){
            $users = array();
        }
            echo $this->twig->render('admin/registrations.twig', array('classes'=>$classes, 'users'=>$users, 'class'=>$class));
    }

    ////////EDIT USERS


    public function getUsersWithRegistrations()
    {

        $db = new DB();
        $users = $db->getUsers();

        foreach ($users as $key=>$user )

        {
            $registrations = $db->getUserRegistrations($user['id']);

            foreach ($registrations as $registration){

                $users[$key]['registrations'][] = $db->getClass($registration['classID']);

            }

        }

        return $users;

    }

    public function editUsers()
    {
        $db = new DB();

        $users = $this->getUsersWithRegistrations();
        $classes = $db->getClasses();


        echo $this->twig->render('admin/editUsers.twig', array('users'=> $users, 'classes'=>$classes));

    }

    public function searchUsers()
    {
        $db = new DB();

        $user = $db->searchUsers($_GET['keyword']);

        echo $this->twig->render('admin/editUsers.twig', array('users'=> $user));

    }


    public function updateUser()
    {
        $DB = new DB();

        var_dump($_POST);


        if($DB->updateUser($_POST['id'],$_POST['name'],$_POST['email'],$_POST['password'], $_POST['birthDate'],
            $_POST['gender'], $_POST['membershipType'], $_POST['admin']))
        {
            header('Location: /editusers');
        }

    }

    public function deleteUser()
    {
        $db = new DB();
        $db->deleteUser($_POST['id']);

    }

    public function addMultipleUsers()
    {
        $uploadService = new Upload();
        $db = new DB();

        $result = $uploadService->uploadFile($_FILES['users_file']);

        //If file was NOT uploaded show message to user and exit
        if ($result['success'] == false){
            echo $this->twig->render('admin/editUsers.twig', ['result'=>$result]);

            die();
        }

        $encodedUsers = file_get_contents(__DIR__.'/../storage/users.json');

        $decodedUsers = json_decode($encodedUsers);

        foreach ($decodedUsers as $user)
        {
            $result = $db->addMutlipleUsers($user);
        }

        echo $this->twig->render('admin/editUsers.twig', ['result'=>$result]);
    }

    public function addUser()
    {
        $db = new DB();

        $result = $db->addUser($_POST, $_FILES['picture']);

        echo $this->twig->render('admin/editUsers.twig', ['result'=>$result]);
    }


    ///////STATS

    public function stats(){

        //get how many times a day was repeated aka the number of visits per day by users
        $hours = array_count_values($this->getHourLogs());

        //get how many times a day was repeated aka the number of visits per day by users
        $days = array_count_values($this->getDayLogs());

        //get how many times a month was repeated aka the number of visits per month by users
        $months = array_count_values($this->getMonthsLogs());

        //get how many times a year was repeated aka the number of visits per year by users
        $years = array_count_values($this->getYearsLogs());

        //sort the array by year
        ksort($years);
        //sort the array by month number
        ksort($months);
        $months = $this->convertMonths($months);
        //sort the array by day number
        ksort($days);
        $days = $this->convertDays($days);
        //sort the array by hour
        ksort($hours);

        echo $this->twig->render('admin/logStatistics.twig', array('years'=>$years,'months'=>$months, 'days'=>$days, 'hours'=>$hours));
    }

    public function postStatsMonth(){

        //get how many times a month was repeated aka the number of visits per month by users
        $months = array_count_values($this->getMonthsLogsFilter());
        //sort the array by month number
        ksort($months);

        $months = $this->convertMonths($months);

        echo json_encode($months);
    }

    public function postStatsYear(){

        //get how many times a month was repeated aka the number of visits per month by users
        $years = array_count_values($this->getYearsLogsFilter());
        //sort the array by month number
        ksort($years);


        echo json_encode($years);
    }

    public function postStatsDay(){

        //get how many times a month was repeated aka the number of visits per month by users
        $days = array_count_values($this->getDaysLogsFilter());
        //sort the array by month number
        ksort($days);
        $days = $this->convertDays($days);

        echo json_encode($days);
    }

    public function postStatsHour(){

        //get how many times a month was repeated aka the number of visits per month by users
        $hours = array_count_values($this->getHoursLogsFilter());
        //sort the array by month number
        ksort($hours);

        echo json_encode($hours);
    }

    public function getYearsLogs()
    {
        $db = new DB();
        //create an array with years as string values
        $yearsDB = $db->getUsersLogsYears();

        foreach ($yearsDB as $year){
            foreach ($year as $r){
                $years[]= $r;
            }
        }
        return $years;
    }

    public function getMonthsLogs()
    {
        $db = new DB();

        $monthsDB = $db->getUsersLogsMonths();
//        , $ageUpperLimit, $ageLowerLimit

        //create an array with months as string values
        foreach ($monthsDB as $mon){
            foreach ($mon as $r){
                $months[]= $r;
            }
        }
        return $months;
    }

    public function getDayLogs()
    {
        $db = new DB();
//        $logs = $db->getUsersLogs();
        $dayLogs = $db->getUsersLogsDays();
        $days = array();
        //create an array with days as string values
        foreach ($dayLogs as $day){
            foreach ($day as $r){
                $days[]= $r;
            }
        }

        return $days;
    }

    public function getHourLogs()
    {
        $db = new DB();
//        $logs = $db->getUsersLogs();
        $hourLogs = $db->getUsersLogsHours();
        $hours = array();
        //create an array with days as string values
        foreach ($hourLogs as $hour){
            foreach ($hour as $r){
                $hours[]= $r;
            }
        }

        return $hours;
    }

    public function getYearsLogsFilter()
    {
        $db = new DB();

        $yearsDB = $db->getUsersLogsYearsFilter();
        $years = array();
        //create an array with months as string values
        foreach ($yearsDB as $year){
            foreach ($year as $r){
                $years[]= $r;
            }
        }
        return $years;
    }

    public function getMonthsLogsFilter()
    {
        $db = new DB();

        $monthsDB = $db->getUsersLogsMonthsFilter();
        $months = array();
        //create an array with months as string values
        foreach ($monthsDB as $mon){
            foreach ($mon as $r){
                $months[]= $r;
            }
        }
        return $months;
    }

    public function getDaysLogsFilter()
    {
        $db = new DB();

        $daysDB = $db->getUsersLogsDaysFilter();
        $days = array();
        //create an array with months as string values
        foreach ($daysDB as $day){
            foreach ($day as $r){
                $days[]= $r;
            }
        }
        return $days;
    }

    public function getHoursLogsFilter()
    {
        $db = new DB();

        $hoursDB = $db->getUsersLogsHoursFilter();
        $hours = array();
        //create an array with months as string values
        foreach ($hoursDB as $hour){
            foreach ($hour as $r){
                $hours[]= $r;
            }
        }
        return $hours;
    }

    public function convertMonths($months)
    {
        foreach ($months as $monthNum=>$value){

            //convert month number to name
            $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
            //use name of the month as the new key of the array
            $months[$dateObj->format('F')] = $months[$monthNum]; // March
            //unset old array key
            unset($months[$monthNum]);

        }
        return $months;
    }

    public function convertDays($days)
    {
        $dayConverter = array(
            1 => 'Sunday',
            2 => 'Monday',
            3 => 'Tuesday',
            4 => 'Wednesday',
            5 => 'Thursday',
            6 => 'Friday',
            7 => 'Saturday'
        );

        foreach ($days as $dayNum=>$value){
            //convert day number to name
            //use name of the month as the new key of the array
            $days[$dayConverter[$dayNum]] = $days[$dayNum];
            //unset old array key
            unset($days[$dayNum]);

        }
        return $days;
    }

    public function userStats(){

        $db = new DB();
        $ageDB = $db->getUsersAge();

        $age = array();
        foreach ($ageDB as $ages){
            foreach ($ages as $r){
                $age[]= $r;
            }
        }
        $age = array_count_values($age);

        $genderDB = $db->getUsersGender();
        $gender = array();
        foreach ($genderDB as $genders){
            foreach ($genders as $r){
                $gender[]= $r;
            }
        }
        $gender = array_count_values($gender);

        echo $this->twig->render('admin/userStats.twig', array('age'=>$age, 'gender'=> $gender));
    }


    /***********LOGS**************/
    public function usersLogs(){

        $db = new DB();
        $logs = $db->getUsersLogs();
        $users = $db->getUsers();

        echo $this->twig->render('admin/logs.twig', array('users'=>$users, 'logs'=>$logs));

    }

    public function realtimeLogs(){
        $db = new DB();
        $logs = $db->getRealtimeLogs();
        $usersID = array();
        foreach ($logs as $log) {
            foreach ($log as $r) {
                $usersID[] = $r;
            }
        }
        $users = array();

        foreach ($usersID as $key=>$id){
            $users[] = $db->getUsersByID($id);
        }
        echo $this->twig->render('realtimeLogs.twig', array('users'=>$users));
    }

    public function searchRealtimeLogs(){

        $db = new DB();
        $logs = $db->getRealtimeLogs();
        $usersID = array();
        foreach ($logs as $log) {
            foreach ($log as $r) {
                $usersID[] = $r;
            }
        }
        $users = array();
        foreach ($usersID as $key=>$id){
//            d($id);
            $test = $db->searchUsersByID($id, $_GET['keyword']);
            if(!empty($test)) {
                foreach ($test as $one){
                    $users[] = $one;
                }
            }
        }
        echo $this->twig->render('admin/realtimeLogs.twig', array('users'=>$users));
    }

    public function searchLogs(){

//        $db = new DB();
//        $logs = $db->getUsersLogs();
//        $users = $db->getUsers();
//
//        echo $this->twig->render('admin/logs.twig', array('users'=>$users, 'logs'=>$logs));
        echo "NEEDS IMPLEMENTATION!";

    }

    /*********USER********/

    public function register()
    {
        $db = new DB();
        $calendarClasses = $db->getClasses();
        $classes = $db->getClasses();
        $userClasses = $this->getUserClasses();
        $allInstructors = $db->getInstructors();

        $classes = $this->beautifyClasses($classes);
        $userClasses = $this->beautifyClasses($userClasses);

        $calendarClasses = $this->beautifyClassesForCalendar($calendarClasses);

        echo $this->twig->render('customer/register.twig', array('calendarClasses'=>$calendarClasses, 'classes'=>$classes, 'allInstructors'=>$allInstructors, 'userClasses'=>$userClasses));

    }

    public function unregisterClass()
    {
        $db = new DB();
        $db->unregisterClass($_POST['userID'], $_POST['classID']);

//        var_dump($_POST);

    }

    public function registerClass()
    {
        $db = new DB();
        $db->registerClass($_POST['userID'], $_POST['classID']);

    }

    public function signin() {
        $db = new DB();
        $date = date_create();
        $user = $db->getUserProfile($_GET['userID']);
        $classes = $this->getUserClasses($_GET['userID']);
        $classes = $this->beautifyClassesForCalendar($classes);
        $test = $db->getUserLogin($_GET['userID']);

        if($test) {

            $db->signout($_GET['userID'],date_format($date, 'Y-m-d H:i:s'));
//            echo $this->twig->render('admin/customerProfile.twig', array('user'=>$user, 'classes'=>$classes));

        } else {
            $db->signin($_GET['userID'],date_format($date, 'Y-m-d H:i:s'));
            echo $this->twig->render('admin/customerProfile.twig', array('user'=>$user, 'classes'=>$classes));
        }

//        2012-06-18 10:34:09
//        d($db->getUserLogin("1"));
//        http://athletics-deree.app/signin?userID=1
//       http://athletics-deree.app/?name1=value1&name2=value2
    }
}