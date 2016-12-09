<?php
/**
 * Created by PhpStorm.
 * User: nourhan
 * Date: 17/11/2016
 * Time: 07:01
 */

namespace Nourhan\Controllers;


use Nourhan\Database\DB;
use Nourhan\Services\SwiftMailer;

class TrainerController extends Controller
{

    public function programRequests()
    {
        $db = new DB();
        if (isset($_GET['keyword'])){
            $pendingRequests = $db->searchPendingProgramRequests($_GET['keyword']);
        } else{
            $pendingRequests = $db->getPendingProgramRequests();
        }
        $pendingRequests = convertGoalsList($pendingRequests);

        echo $this->twig->render('trainer/pendingRequests.twig', array('requests'=>$pendingRequests));
    }

    public function trainerResponse()
    {
        $db = new DB();

        $result = $db-> trainerResponse($_POST['id'], $_POST['trainerComments'], $_SESSION['user']['id']);
        if($result){
            $mailer = new SwiftMailer();
            $result = $mailer->sendEmail($_POST);
        }
        echo json_encode($result);

    }

    public function finalizedProgramRequests()
    {
        $db = new DB();
        if (isset($_GET['keyword'])){
            $finalizedRequests = $db->searchFinalizedProgramRequests($_GET['keyword']);
        } else{
            $finalizedRequests = $db->getFinalizedProgramRequests();
        }
        $finalizedRequests = convertGoalsList($finalizedRequests);

        echo $this->twig->render('trainer/finalizedRequests.twig', array('requests'=>$finalizedRequests));
    }
    
}