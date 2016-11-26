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

        echo $this->twig->render('customer/profile.twig', array('logs'=>$logs, 'classes'=>$classes));
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


/** Workout Program **/

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
        $shipping = 0.00;
        $total = $price + $shipping;

//        d($price);
//        d($shipping);
//        d($total);

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
        $redirectUrls->setReturnUrl("http://athletics-deree.app/profile?success=true")
            ->setCancelUrl("http://athletics-deree.app/profile?success=false");

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        try{
            $payment->create(
                new \PayPal\Rest\ApiContext(
                    new \PayPal\Auth\OAuthTokenCredential(
                        'AZ7z5y3rt0tTnl8ToZtYRo9AJsbPcbrQuV01fm0hAXC53BDBLtCrNAwpV4FSBH2ect6pH8OPTdJD56qH',
                        'ELBq1gvnUzzcmeS59_veLBzXZuwVv0_b-jY5sAeMPSJME17B5KcTYDWOhyKA0E1Mwv23fP7FstKCBro6'
                    )
                )
            );
        }catch (Exception $e){
//            d($e);
//            ddd($e->getMessage());
        }

        redirect($payment->getApprovalLink());
//        redirect('/testing');
    }
}