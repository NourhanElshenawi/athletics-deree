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
        ddd($pendingRequests);
        echo $this->twig->render('trainer/pendingRequests.twig', array('requests'=>$pendingRequests));
    }
    
}