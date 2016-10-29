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
//    var_dump($classes[0]['days']);
//        if ($classes[])

        echo $this->twig->render('fitnessProgram.twig', array('classes'=> $classes));
    }
/*****CUSTOMER*****/
    public function profileStats()
    {


//        $DB = new DB();
//        $carouselImages = $DB->getCarousel();

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
//        var_dump($vals);
        for ($x=0; $x<count($array);){

            $x++;
        }

        echo $this->twig->render('customer/profile.twig', array('vals'=>$vals));
    }

    public function login()
    {
        echo $this->twig->render('login.twig');
    }

    public function verifyResponse()
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

    /***********ADMIN************/

    ////////EDIT CLASS SCHEDULE

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


        $instructors = array();

        foreach ($classes as $class ){

            $id = "".$class["instructorID"]."";
            $instructors[]= $DB->getInstructor($id);

    }

        echo $this->twig->render('admin/editSchedule.twig', array('classes'=> $classes, 'instructors'=> $instructors, 'allInstructors'=>$allInstructor));
    }

    public function deleteClass()
    {
        $db = new DB();
        $db->deleteClass($_POST['id']);

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

    ////////EDIT USERS



    public function editUsers()
    {
        $db = new DB();

        $users = $db->getUsers();

        echo $this->twig->render('admin/editUsers.twig', array('users'=> $users));

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

}