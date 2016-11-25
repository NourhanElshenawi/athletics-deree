<?php
/**
 * Created by PhpStorm.
 * User: antony
 * Date: 11/17/16
 * Time: 4:37 PM
 */

namespace Nourhan\Controllers;

use Nourhan\Database\DB;


class UserController extends Controller
{

/** Signin **/
    public function signin() {
        $db = new DB();
        $date = date_create();
        $user = $db->getUserProfile($_GET['userID']);
        $classes = $this->getUserClasses($_GET['userID']);
        $classes = $this->beautifyClassesForCalendar($classes);
        //check if user is already in gym
        $test = $db->userInGym($_GET['userID']);

        if($test) {
            //if user is in the gym log him out
            $db->signout($_GET['userID'],date_format($date, 'Y-m-d H:i:s'));
//            echo $this->twig->render('admin/customerProfile.twig', array('user'=>$user, 'classes'=>$classes));

        } else {
            //if user is not in the gym log him in
            $db->signin($_GET['userID'],date_format($date, 'Y-m-d H:i:s'));
            echo $this->twig->render('admin/customerProfile.twig', array('user'=>$user, 'classes'=>$classes));
        }

//        2012-06-18 10:34:09
//        d($db->getUserLogin("1"));
//        http://athletics-deree.app/signin?userID=1
//       http://athletics-deree.app/?name1=value1&name2=value2
    }

    public function login()
    {
        echo $this->twig->render('login.twig');
    }

    public function userLogin()
    {
        // check if a user is logged in at the moment. if he is the session user should be set
        if(isset($_SESSION['user'])){

            header('Location: /profile');
        }
        // if a user is not logged in attempt to login
        else {
            $db = new DB();
            //get any user with the collected credentials
            $user = $db->getUser($_POST["username"], $_POST["password"]);

            //check if there was indeed a user found matching these credentials
            if (empty($user)) {
                $error['message'] = "Invalid Credentials!";
                echo $this->twig->render('login.twig', array('error'=>$error));
            }
            //if a user was found with these credentials, set the session user variable with all his information
            else {
                $_SESSION['user'] = $user;
                // FIXME: Call to undefined function
                redirect("/profile");
            }

        }
    }

    public function logout()
    {
        //remove session user variable as the user will logout
        unset($_SESSION['user']);
        // TODO: Use global redirect function
        header('Location: /login');
    }


/** Profile**/
    public function profileStats()
    {

        $db = new DB();
        $logs = $db->getUserMonthlyVisits($_SESSION['user']['id']);

        $logs = $this->convertDateToString($logs);

        $logs = array_count_values($logs);
        $logs = $this->convertMonths($logs);


        $classes = $this->getUserClasses($_SESSION['user']['id']);
        $classes = $this->beautifyClassesForCalendar($classes);

        echo $this->twig->render('customer/profile.twig', array('logs'=>$logs, 'classes'=>$classes));
    }

    public function getUserClasses($id) {

        $db = new DB();

        $userRegistrations = $db->getUserRegistrations($id);
        $classes = array();

        foreach ($userRegistrations as $registration){

            $classes[] = $db->getClass($registration['classID']);

        }

        return $classes;
    }

    public function profile()
    {
        echo $this->twig->render('customer/profile.twig');
    }


/** Requesting workout programs **/
    public function requestProgram ()
    {
        echo $this->twig->render('customer/requestProgram.twig');
    }

    public function submitProgram ()
    {
        $db = new DB();

        $db->createProgramRequest($_POST);
    }


/** Registration for classes **/
    public function register()
    {
        $db = new DB();

        $calendarClasses = $db->getClasses();
        $classes = $db->getClasses();
        $userClasses = $this->getUserClasses($_SESSION['user']['id']);

        $classes = $this->beautifyClasses($classes);
        $userClasses = $this->beautifyClasses($userClasses);
        $calendarClasses = $this->beautifyClassesForCalendar($calendarClasses);
        d($userClasses);

        echo $this->twig->render('customer/register.twig', array('calendarClasses'=>$calendarClasses, 'classes'=>$classes,'userClasses'=>$userClasses));
    }

    public function unregisterClass()
    {
        $db = new DB();
        echo  json_encode($db->unregisterClass($_POST['userID'], $_POST['classID']));

    }

    public function registerClass()
    {
        $db = new DB();
        echo json_encode($db->registerClass($_POST['userID'], $_POST['classID']));

    }


/** Workout Program history **/

    public function programHistory()
    {
        $db = new DB();
        $allRequests = $db->getUserProgramRequests($_SESSION['user']['id']);
        foreach ($allRequests as $key=>$request){
            $goal = array();
            $goal['developMuscleStrength']= $request['developMuscleStrength'];
            $goal['rehabilitateInjury'] = $request['rehabilitateInjury'];
            $goal['overallFitness'] = $request['overallFitness'];
            $goal['loseBodyFat'] = $request['loseBodyFat'];
            $goal['startExerciseProgram'] = $request['startExerciseProgram'];
            $goal['designAdvanceProgram'] = $request['designAdvanceProgram'];
            $goal['increaseFlexibility'] = $request['increaseFlexibility'];
            $goal['sportsSpecificTraining'] = $request['sportsSpecificTraining'];
            $goal['increaseMuscleSize'] = $request['increaseMuscleSize'];
            $goal['cardioExercise'] = $request['cardioExercise'];
            asort($goal);
            $allRequests[$key]['goals'] = $goal;
        }

        echo "hiii";
        echo $this->twig->render('customer/previousProgramRequests.twig', array('requests'=>$allRequests));
    }

/** General functions **/

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


    /** Android **/

    public function androidLogin()
    {
        $db = new DB();

        $user = $db->androidLogin($_POST["username"], $_POST["password"]);

        echo json_encode($user);
    }

    public function androidViewProgram()
    {
        $db = new DB();

        $result = $db->androidViewProgram($_POST["ID"]);

        echo json_encode($result);

//        $result = [];
//        $result["success"] = 1;
//        $result["message"] = "Success. We did it!";
//        $result["program"] = "Trainer comments for program go here.";
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

    public function convertDateToString($dates)
    {
        $date = array();
        foreach ($dates as $dt){
            foreach ($dt as $r){
                $date[]= $r;
            }
        }
        return $date;
    }

}