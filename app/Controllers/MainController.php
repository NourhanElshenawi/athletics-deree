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

    public function calendar()
    {
        echo $this->twig->render('calendar.twig');
    }

    public function fitnessProgram()
    {
        $db = new DB();
        $classes = $db->getClasses();
//        $days[]= array();
//        var_dump($classes);
        foreach ($classes as $key=>$class) {
//            var_dump($class);

            $class['days']=array();

            if($class['monday']){
                $classes[$key]['days'][]="1";
//                echo $class['monday'];
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
//    var_dump($classes[0]['days']);
//        if ($classes[])

        echo $this->twig->render('fitnessProgram.twig', array('classes'=> $classes));
    }
/*****CUSTOMER*****/
    public function profileStats()
    {
        $array = array("January", "January", "January", "February", "February", "March");
        $vals = array_count_values($array);
        var_dump($vals);
        for ($x=0; $x<count($array);){

            $x++;
        }

        echo $this->twig->render('customer/profile.twig', array('vals'=>$vals));
    }

    public function login()
    {
        echo $this->twig->render('login.twig');
    }

    public function adminLogin()
    {
// your secret key
        $secret = "6Lel3ggUAAAAACT3Xz7dfhUhvFGALwDrXgtwFeON";
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
            echo "Hi " . $_POST["username"] . " (" . $_POST["password"] . "), thanks for submitting the form!";
        } else {
            //REDIRECT TO ERROR'
            echo "not submitted";
        }
    }

    public function profile()
    {
        echo $this->twig->render('customer/profile.twig');
    }

    /***********ADMIN************/

    public function editSchedule()
    {
        $DB = new DB();
        $classes = $DB->getClasses();
        $allInstructor = $DB->getInstructors();

        foreach ($classes as $key=>$class ){

            $temp = ($class['currentCapacity']*100)/$class['capacity'];
            $temp2 = $class['currentCapacity'];

            $classes[$key]['currentCapacityPercentage'] = $temp;
            $classes[$key]['currentCapacity'] = $temp2;
        }


        $instructors = array();

        foreach ($classes as $class ){

            $id = "".$class["instructorID"]."";
            $instructors[]= $DB->getInstructor($id);

    }

        echo $this->twig->render('admin/editSchedule.twig', array('classes'=> $classes, 'instructors'=> $instructors, 'allInstructors'=>$allInstructor));
    }


    public function editClass()
    {
//        var_dump($_POST);

        if(isset($_POST['edit'])){
            echo "edit working!!!";
        }
//        elseif (isset($_GET['delete'])){
//            echo "delete working!!!";
//        }

        echo $this->twig->render('admin/editClass.twig');
    }


    public function updateClass()
    {
        var_dump($_POST);

        $DB = new DB();
        if($DB->updateClass($_POST['id'],$_POST['duration'],$_POST['startTime'],$_POST['capacity'], $_POST['instructor'])) {
            echo "Success";
    }

    }


}