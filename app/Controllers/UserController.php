<?php
/**
 * Created by PhpStorm.
 * User: antony
 * Date: 11/17/16
 * Time: 4:37 PM
 */

namespace Nourhan\Controllers;

require __DIR__ . '/../start.php';

use Nourhan\Database\DB;

use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;



class UserController extends Controller
{


/** Signin **/
    public function signin() {
        $db = new DB();
        $date = date_create();
        $user = $db->getUserProfile($_GET['userID']);
        $classes = $this->getUserClasses($_GET['userID']);
        $classes = beautifyClassesForCalendar($classes);
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

    public function userLogin()
    {
        // check if a user is logged in at the moment. if he is the session user should be set
        if(isset($_SESSION['user'])){
            redirect('/profile');
        }
        // if a user is not logged in attempt to login
        else {
            $db = new DB();
            //get any user with the collected credentials
            $user = $db->getUser($_POST["username"], $_POST["password"]);

            //check if there was indeed a user found matching these credentials
            if (empty($user)) {
                $error['message'] = "Invalid Credentials!";
                echo $this->twig->render('login.twig', array('error'=>$error));
            }
            //if a user was found with these credentials, set the session user variable with all his information
            else {
                $_SESSION['user'] = $user;
                redirect('/profile');
            }

        }
    }

    public function logout()
    {
        //remove session user variable as the user will logout
        unset($_SESSION['user']);
        redirect('/login');
    }


/** Profile**/
    public function profileStats()
    {

        $db = new DB();
        $logs = $db->getUserMonthlyVisits($_SESSION['user']['id']);

        $logs = $this->convertDateToString($logs);

        $logs = array_count_values($logs);
        $logs = $this->convertMonths($logs);


        $classes = $this->getUserClasses($_SESSION['user']['id']);
        $classes = beautifyClassesForCalendar($classes);

        $dateOfPayment = explode(" ", $db->getLastPaymentByUser($_SESSION['user']['id'])['date'])[0];
        $periodCoveredByPayment = daysDateDifference(date("Y-m-d"),$dateOfPayment);

        if($periodCoveredByPayment>= 31){
            $needToPay = true;
        }else {
            $needToPay = false;
        }


        $paymentSuccess = $db->getLastPaymentByUser($_SESSION['user']['id']);
        if(isset($_GET['success'])){
            if($db->addPayment($_GET['paymentId'], $_GET['token'], $_GET['PayerID'], $_SESSION['user']['id'])){
                $paymentSuccess = true;
            } else{
                $paymentSuccess = "Please contact support regarding your last Payment!";
            }
        } else if (!isset($_GET['success'])){
            $paymentSuccess = false;
        }

        echo $this->twig->render('customer/profile.twig', array('logs'=>$logs, 'classes'=>$classes, 'needToPay'=>$needToPay, 'paymentSuccess'=>$paymentSuccess));
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


/** Registration for classes **/
    public function register()
    {
        $db = new DB();

        $calendarClasses = $db->getClasses();
        $classes = $db->getClasses();
        $userClasses = $this->getUserClasses($_SESSION['user']['id']);

        $classes = beautifyClasses($classes);
        $userClasses = beautifyClasses($userClasses);
        $calendarClasses = beautifyClassesForCalendar($calendarClasses);
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

    /**
     * Personal Stas
     */

    public function personalStats()
    {
        $this->averageTimeSpentPerVisit();
    }
    public function totalHoursSpent()
    {

    }
    public function averageTimeSpentPerVisit()
    {
        $db = new DB();
        $logTimes = $db->getUserLogsTime($_SESSION['user']['id']);
        d($logTimes);
        $hours = array();
        $minutes = array();
        $seconds = array();

        foreach ($logTimes as $log){
            $date1 = date("Y-m-d")." ". $log['TIME (logout)'];
            $date2 = date("Y-m-d")." ". $log['TIME (login)'];
            $diff = minutesTimeDifference($date1, $date2);
            $hours[] = $diff->h;
            $minutes[] = $diff->m;
            $seconds[] = $diff->s;
        }
        $totalHours = array_sum($hours);
        $avgHours = $totalHours/count($hours);
        d($avgHours);
        $totalMins = array_sum($minutes);
        $avgMins = $totalMins/count($minutes);
        d($avgMins);

        $totalSecs = array_sum($seconds);
        $avgSecs = $totalSecs/count($seconds);
        d($avgSecs);


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


/** View Workout Program **/

    public function programHistory()
    {
        $db = new DB();
        $allRequests = $db->getUserProgramRequests($_SESSION['user']['id']);
        $allRequests = convertGoalsList($allRequests);

        echo $this->twig->render('customer/previousProgramRequests.twig', array('requests'=>$allRequests));
    }

    public function currentProgramRequest()
    {
        $db = new DB();
        $allRequests = $db->getUserCurrentProgram($_SESSION['user']['id']);
        $allRequests = convertGoalsList($allRequests);

        echo $this->twig->render('customer/currentProgramRequest.twig', array('requests'=>$allRequests));
    }


    /** Android **/

    public function androidLogin()
    {
        $db = new DB();

        $user = $db->androidLogin($_POST["username"], $_POST["password"]);

        echo json_encode($user);
    }

    public function androidViewProgram()
    {
        $db = new DB();

        $result = $db->androidViewProgram($_POST["ID"]);

        echo json_encode($result);

//        $result = [];
//        $result["success"] = 1;
//        $result["message"] = "Success. We did it!";
//        $result["program"] = "Trainer comments for program go here.";
    }

    public function convertMonths($months)
    {
        foreach ($months as $monthNum=>$value){

            //convert month number to name
            $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
            //use name of the month as the new key of the array
            $months[$dateObj->format('F')] = $months[$monthNum]; // March
            //unset old array key
            unset($months[$monthNum]);

        }
        return $months;
    }

    public function convertDateToString($dates)
    {
        $date = array();
        foreach ($dates as $dt){
            foreach ($dt as $r){
                $date[]= $r;
            }
        }
        return $date;
    }

    /**
     * Pay with PayPal
     */

    public function pay()
    {
        $product = 'DEREE Gym Subscription Fee';
        $price = (float)10;
        $shipping = 2.00;
        $total = $price + $shipping;

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName($product)
            ->setCurrency('EUR')
            ->setQuantity(1)
            ->setPrice($price);

        $itemList = new ItemList();
        $itemList->setItems([$item]);

        $details = new Details();
        $details->setShipping($shipping)
                ->setSubtotal($price);

        $amount = new Amount();
        $amount->setCurrency('EUR')
            ->setTotal($total)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("DEREE Gym Monthly Fee")
            ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl("http://athletics-deree.app/profile?paymentSuccess=true")
            ->setCancelUrl("http://athletics-deree.app/profile?paymentSuccess=false");

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        try{
            $payment->create(
                new \PayPal\Rest\ApiContext(
                    new \PayPal\Auth\OAuthTokenCredential(
                        'Ad6QnpfuKV6qbAKPj0Uy7OykghBAQCBN5_MO8l2_Croc7PfI9pubbrbzqOmx5oerciLLXeFHUvN2RdPk',
                        'EGyc8_npQKix-febAo1c1eDl1y-21hO1n2hzhW5AZI_ZIFrPiBccz-Cd9PaqG2YOqIosh0Zi1Qguqusb'
                    )
                )
            );
        }catch (Exception $e){
//            d($e);
//            ddd($e->getMessage());
        }

        $paymentURL = $payment->getApprovalLink();

//        redirect($payment->getApprovalLink());
//        redirect('/testing');
    }
}