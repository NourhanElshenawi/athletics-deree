<?php
/**
 * Created by PhpStorm.
 * User: nourhan
 * Date: 20/11/2016
 * Time: 06:44
 */

namespace Nourhan\Controllers;

use Nourhan\Database\DB;

class AdminController extends Controller
{

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

    /***********ADMIN************/

    ////////EDIT CLASS SCHEDULE
    public function editSchedule()
    {
        $db = new DB();
        $classes = $db->getClasses();
//        $allInstructor = $db->getInstructors();
        $classes = $this->beautifyClasses($classes);
        $allInstructors = $db->getInstructors();
//        $instructors = array();
//        foreach ($classes as $class ){
//            $id = "".$class["instructorID"]."";
//            $instructors[]= $db->getInstructor($id);
//        }
        echo $this->twig->render('admin/editSchedule.twig', array('classes'=> $classes,'allInstructors'=> $allInstructors));
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

        $classes = $db->searchClasses($_GET['keyword']);
        $classes = $this->beautifyClasses($classes);
        $allInstructors = $db->getInstructors();

        echo $this->twig->render('admin/editSchedule.twig', array('classes'=> $classes, 'allInstructors'=> $allInstructors));
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

            echo $this->twig->render('admin/registrations.twig', array('classes'=>$classes, 'users'=>$users, 'selectedClass'=>$class));
        }

    }

//    public function searchRegistrations()
//    {
//        $db = new DB();
//        $classes = $db->getClasses();
//
//        $users = $db->searchClassRegistrations($_GET['classID'], $_GET['keyword']);
//        $class = $db->getClass($_GET['classID']);
//        if (empty($users)){
//            $users = array();
//        }
//        echo $this->twig->render('admin/registrations.twig', array('classes'=>$classes, 'users'=>$users, 'class'=>$class));
//    }

    ////////EDIT USERS
    public function getUsersWithRegistrations($users)
    {
        $db = new DB();

        foreach ($users as $key=>$user){

            $users[$key]['classes']= $db->getUserRegistrations($user['id']);
        }

        return $users;
    }

    public function editUsers()
    {
        $db = new DB();
        if(isset($_GET['keyword'])){

            $users = $db->searchUsers($_GET['keyword']);
            $users = $this->getUsersWithRegistrations($users);
            $classes = $db->getClasses();
        }
        else{
            $users = $db->getUsers();
            $users = $this->getUsersWithRegistrations($users);
            $classes = $db->getClasses();
        }

        echo $this->twig->render('admin/editUsers.twig', array('users'=> $users, 'classes'=> $classes));

    }

//    public function searchUsers()
//    {
//        $db = new DB();
//
//        $users = $db->searchUsers($_GET['keyword']);
//        $users = $this->getUsersWithRegistrations($users);
//        $classes = $db->getClasses();
//
//        echo $this->twig->render('admin/editUsers.twig', array('users'=> $users, 'classes'=> $classes));
//
//    }


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

    //Logs
    public function usersLogs(){

        $db = new DB();
        if(isset($_GET['keyword'])){
            $logs = $db->getLogsByKeyword($_GET['keyword']);
        }
        else {
            $logs = $db->getUsersLogs();
        }
        echo $this->twig->render('admin/logs.twig', array('logs'=>$logs));
    }

    public function manualLogUser()
    {
        $db = new DB();

        if(isset($_GET['keyword'])){

            $users = $db->searchUsers($_GET['keyword']);
            foreach ($users as $key=>$user){
                if(empty($db->userInGym($user['id']))){
                $users[$key]['inGym'] = false;
            } else{
                $users[$key]['inGym'] = true;
            }
            }
        }
        else{

            $users = $db->getUsers();
            foreach ($users as $key=>$user){
                if(empty($db->userInGym($user['id']))){
                    $users[$key]['inGym'] = false;
                } else{
                    $users[$key]['inGym'] = true;
                }
            }
        }

        echo $this->twig->render('admin/manualLogUser.twig', array('users'=> $users));
    }

    public function signin()
    {
        $db = new DB();
        $date = date_create();

        if(empty($db->userInGym($_POST['id']))) {
            $db->signin($_POST['id'], date_format($date, 'Y-m-d H:i:s'));
            $result['success'] = true;
            $result['msg'] = "User Logged in Successfully!";
            echo json_encode($result);
        } else {
            $result['success'] = false;
            $result['msg'] = "User Already In The Gym";
            echo json_encode($result);
        }
    }
    public function signout()
    {
        $db = new DB();
        $date = date_create();

        if(!empty($db->userInGym($_POST['id']))){
            $db->signout($_POST['id'],date_format($date, 'Y-m-d H:i:s'));

            $result['success'] = true;
            $result['msg'] = "User Logged out Successfully!";
            echo json_encode($result);
        } else{
            $result['success'] = false;
            $result['msg'] = "User Not In The Gym";
            echo json_encode($result);
        }

    }



}