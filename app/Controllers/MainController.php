<?php
namespace Kourtis\Controllers;

use Kourtis\Database\DB;

class MainController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
//        $DB = new DB();
//        $latestPosts = $DB->getNewestPosts(6);
//        $carouselPosts = $DB->getCarouselPosts();

//        set_time_limit(0);
//        ini_set('default_socket_timeout', 5);
//        session_start();

        $clientID = '30cb4af47d4545f7b96aac046f59b157';
        $clientSecret = '38a96dc94cc940b8bf9bb3554d0e07de';
        $redirectURI = 'http://test.app';
        $ImageDirectory = 'pics/';

        if( isset($_GET['code']) ) {
            $code = $_GET['code'];
            $url = "https://api.instagram.com/oauth/access_token";
            $access_token_settings = array('clientID' => $clientID,
                'clientSecret' => $clientSecret,
                'grantType' => 'authorization_code',
                'redirectURI' => $redirectURI,
                'code' => $code);

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $access_token_settings);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        }

        echo $this->twig->render('index.twig', array('clientID'=>$clientID, 'client_Secret'=>$clientSecret, 'redirectURI'=>$redirectURI, 'ImageDirectory'=>$ImageDirectory));
    }

    public function contact()
    {
        echo $this->twig->render('contact.twig');
    }

    public function postContactDetails()
    {
        var_dump($_POST);
    }
    
}