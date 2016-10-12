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

     //////////////////////
    public function includeInCarousel()
    {
        echo "HIIII";
//        $arr = $_POST['inc'];
        $incl = $_GET['key1'];
        echo ".........";
//        var_dump($_GET);
        $position = $_GET['position'];
        echo "ID: ".$incl;
        echo "POSITION".$position;
//        echo "ID: "+$incl;
//        echo "POSITION: "+$position;
        $DB = new DB();
            $DB->includeInCarousel($incl, $position);

        $carouselImages = $DB->getCarousel();
        $notIncludedCarouselImages = $DB->getNotIncludedCarousel();
        echo $this->twig->render('admin2.twig', array('carouselImages'=> $carouselImages, 'notIncludedCarouselImages'=> $notIncludedCarouselImages));
    }
   /////////////////////////////


    //////////////////////
    public function removeFromCarousel()
    {
        echo "RIIII";
        $notIncl = $_GET['key1'];

        echo ".........";
//        echo "ID: ".$notIncl;
//        echo "POSITION: "+$position;
        $DB = new DB();
        $DB->removeFromCarousel($notIncl);

        $carouselImages = $DB->getCarousel();
        $notIncludedCarouselImages = $DB->getNotIncludedCarousel();
        echo $this->twig->render('admin2.twig', array('carouselImages'=> $carouselImages, 'notIncludedCarouselImages'=> $notIncludedCarouselImages));
    }
    /////////////////////////////


    public function deleteFromCarousel()
    {
        echo "DIIII";
        $del = $_GET['key1'];

        echo ".........";
//        echo "ID: ".$notIncl;
//        echo "POSITION: "+$position;
        $DB = new DB();
        $DB->deleteCarouselImage($del);

        $carouselImages = $DB->getCarousel();
        $notIncludedCarouselImages = $DB->getNotIncludedCarousel();
        echo $this->twig->render('admin2.twig', array('carouselImages'=> $carouselImages, 'notIncludedCarouselImages'=> $notIncludedCarouselImages));
    }
    /////////////////////////////

    public function upload()
    {
        if(isset($_POST['submit'])){
            $upload = new Upload();
        }  else {
            if($_POST['included'] == "Included"){
                $included = 1;
            } else $included = 0;
            $DB = new DB();
            $DB->updateCarousel($_POST['id'], $_POST['position'], $included) ;
        }

        $carouselImages = $DB->getCarousel();
        $allCarouselImages = $DB->getAllCarousel();
        echo $this->twig->render('admin.twig', array('carouselImages'=> $carouselImages, 'allCarouselImages'=> $allCarouselImages));
    }
}