<?php
namespace Nourhan\Controllers;

use Nourhan\Database\DB;
use Nourhan\ReCaptcha;


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

            $class['days']=array();

            if($class['monday']){
                $classes[$key]['days'][]="1";
            }
            if($class['tuesday']){
                $classes[$key]['days'][]="2";
            }
            if($class['wednesday']){
                $classes[$key]['days'][]="3";
            }
            if($class['thursday']){
                $classes[$key]['days'][]="4";
            }
            if($class['friday']){
                $classes[$key]['days'][]="5";
            }
        }

        return $classes;
    }
  /** Admin **/
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
        echo $this->twig->render('realtimeLogs.twig', array('users'=>$users));
    }


}