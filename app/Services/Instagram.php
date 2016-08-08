<?php
namespace Kourtis\Services;

class Instagram
{

        // API Info
    protected $clientID;
    protected $clientSecret;
    protected $redirectURI;
    protected $ImageDirectory;

    //extras
    protected $userID;
    protected $access_token;
    protected $username;



    /**
     * DB constructor. By default connect to Homestead virtual DB server and to the 'kourtis' database schema.
     * @param string $servername
     * @param string $port
     * @param string $dbname
     * @param string $username
     * @param string $password
     */
    public function __construct($clientID = '30cb4af47d4545f7b96aac046f59b157', $clientSecret = '38a96dc94cc940b8bf9bb3554d0e07de', $redirectURI = 'http://test.app', $ImageDirectory = 'pics/' )
    {
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;
        $this->redirectURI = $redirectURI;
        $this->ImageDirectory = $ImageDirectory;

        $this->connect();
    }

    public function connect()
    {

        if( isset($_GET['code']) ) {
            $code = $_GET['code'];
            $url = "https://api.instagram.com/oauth/access_token";
            $access_token_settings = array('client_id' => $this->clientID,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $this->redirectURI,
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
            $this->userID =  $results['user']['id'];
            $this->access_token = $results['access_token'];
            echo "ID: ".$this->userID;
    }




//getImages($this->userID, $access_token);


}

    function getUserID(){
        return $this->userID;
    }
    function getClientID(){
        return $this->clientID;
    }

    function getAccessToken(){
        return $this->access_token;
    }

    function getClientSecret(){
        return $this->clientSecret;
    }

    function getRedirectURI(){
        return $this->redirectURI;
    }

    function getImageDirectory(){
        return $this->ImageDirectory;
    }



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
        $instagramInfo = $this->connectToInstagram($url);
        $results = json_decode($instagramInfo, true);

        var_dump($instagramInfo);

        foreach($results['data'] as $item){
            $image_url = $item['images']['low_resolution']['url'];
            echo '<img src=" '.$image_url.' "/> <br/>';
            var_dump($item);
        }
    }


}