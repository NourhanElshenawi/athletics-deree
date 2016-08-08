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

        // API Info
        define('clientID', '30cb4af47d4545f7b96aac046f59b157');
        define('clientSecret', '38a96dc94cc940b8bf9bb3554d0e07de');
        define('redirectURI', 'http://test.app');
        define('ImageDirectory', 'pics/');


        echo $this->twig->render('index.twig', array('clientID'=>clientID, 'client_Secret'=>clientSecret, 'redirectURI'=>redirectURI, 'ImageDirectory'=>ImageDirectory));

        // to connect to the api whenever we want, example: every time we request recent pictures
        function connectToInstagram($url){
            $ch = curl_init();

            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 2,
            ));

            $result = curl_exec($ch);
            curl_close($ch);

            return $result;
        }

        //get the recent pictures of the authenticated user
        function getImages($userID, $access_token){
            $url = "https://api.instagram.com/v1/users/".$userID."/media/recent/?access_token=".$access_token;
            //for a specific number of pictures use the url below
//            $url = "https://api.instagram.com/v1/users/".$userID."/media/recent/?access_token=".$access_token."&count=5";
            $instagramInfo = connectToInstagram($url);
            $results = json_decode($instagramInfo, true);

            var_dump($instagramInfo);

            foreach($results['data'] as $item){
                $image_url = $item['images']['low_resolution']['url'];
                echo '<img src=" '.$image_url.' "/> <br/>';
                var_dump($item);
            }
        }


        if( isset($_GET['code']) ) {
            $code = $_GET['code'];
            $url = "https://api.instagram.com/oauth/access_token";
            $access_token_settings = array('client_id' => clientID,
                'client_secret' => clientSecret,
                'grant_type' => 'authorization_code',
                'redirect_uri' => redirectURI,
                'code' => $code);

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $access_token_settings);  // setting up
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // true if we go live

            $result = curl_exec($curl);
            curl_close($curl);

            $results = json_decode($result, true);
            var_dump($results);
            $userID =  $results['user']['id'];
            $access_token = $results['access_token'];
            echo "ID: ".$userID;
            getImages($userID, $access_token);
        }
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