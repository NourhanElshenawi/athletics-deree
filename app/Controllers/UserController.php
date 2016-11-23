<?php
/**
 * Created by PhpStorm.
 * User: antony
 * Date: 11/17/16
 * Time: 4:37 PM
 */

namespace Nourhan\Controllers;

use Nourhan\Database\DB;
use Nourhan\ReCaptcha;

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

    public function verifyResponse()
    {
// your secret key
        $secret = "6Lel3ggUAAAAACT3Xz7dfhUhvFGALwDrXgtwFeON";
//        $secret = "6Ld91QgUAAAAAIRUcZnJqHYO836O9dCCesrdMgLg"; //for productions
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


/** Profile**/
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
}