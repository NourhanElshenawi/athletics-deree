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
        echo $this->twig->render('fitnessProgram.twig');
    }

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
        var_dump($vals);
        for ($x=0; $x<count($array);){
//            echo $array[$x];
//            echo "</br>";
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
//            echo "Hi " . $_POST["username"] . " (" . $_POST["password"] . "), thanks for submitting the form!";
            $db = new DB();
            $user = $db->getUser($_POST["username"], $_POST["password"]);
//            echo $this->twig->render('customer/profile.twig');

//            if(empty($user)){
//                echo "user does not exist";
//            }
//            else {
//
////                $array = array("January", "January", "January", "February", "February", "March");
////                $vals = array_count_values($array);
////                var_dump($vals);
////                for ($x=0; $x<count($array);){
//////            echo $array[$x];
//////            echo "</br>";
////                    $x++;
////                }
////                echo $this->twig->render('customer/profile.twig', array('user'=>$user, 'vals'=>$vals));
//                echo $this->twig->render('login.twig');
//            }
            $this->profileStats();
        } else {
            //REDIRECT TO ERROR'
            echo "not submitted";
        }
    }

    public function profile()
    {
        echo $this->twig->render('customer/profile.twig');
    }
    public function car()
    {
        var_dump($_POST);


        $DB = new DB();
        $carouselImages = $DB->getCarousel();
        $notIncludedCarouselImages = $DB->getNotIncludedCarousel();
        echo $this->twig->render('carousel.twig', array('carouselImages'=> $carouselImages, 'notIncludedCarouselImages'=> $notIncludedCarouselImages));
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
//        elseif (isset($_GET['view'])){
//            echo "view working!!!";
//        }
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

//        if(isset($_POST['edit'])){
//            echo "edit working!!!";
//        }
//        elseif (isset($_GET['view'])){
//            echo "view working!!!";
//        }
//        elseif (isset($_GET['delete'])){
//            echo "delete working!!!";
//        }

//        echo $this->twig->render('admin/editClass.twig');
    }


}