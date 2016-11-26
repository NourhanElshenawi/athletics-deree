<?php
/**
 * Created by PhpStorm.
 * User: nourhan
 * Date: 17/11/2016
 * Time: 07:01
 */

namespace Nourhan\Controllers;


use Nourhan\Database\DB;

class TrainerController extends Controller
{

    public function programRequests()
    {
        $db = new DB();
        $pendingRequests = $db->getPendingProgramRequests();
        $pendingRequests = convertGoalsList($pendingRequests);

        echo $this->twig->render('trainer/pendingRequests.twig', array('requests'=>$pendingRequests));
    }

    public function trainerResponse()
    {
        $db = new DB();
        echo json_encode($db-> trainerResponse($_POST['id'], $_POST['trainerComments']));
//        $db-> trainerResponse($_POST['id'], $_POST['trainerComments']);

    }
    
}