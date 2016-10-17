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

    public function home()
    {
        $DB = new DB();
        $carouselImages = $DB->getCarousel();

        echo $this->twig->render('home.twig', array('carouselImages'=> $carouselImages));
    }

    public function admin()
    {
        $DB = new DB();
        $carouselImages = $DB->getCarousel();
        $allCarouselImages = $DB->getAllCarousel();
        echo $this->twig->render('admin.twig', array('carouselImages'=> $carouselImages, 'allCarouselImages'=> $allCarouselImages));
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

        echo $this->twig->render('admin/editSchedule.twig', array('classes'=> $classes, 'instructors'=> $instructors));
    }


    public function editClass()
    {
//        var_dump()

        if(isset($_GET['edit'])){
            echo "edit working!!!";
        }
        elseif (isset($_GET['view'])){
            echo "view working!!!";
        }
        elseif (isset($_GET['delete'])){
            echo "delete working!!!";
        }

        echo $this->twig->render('admin/editClass.twig');
    }

}