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
        foreach ($pendingRequests as $key=>$request){
            $goal = array();
            $goal['developMuscleStrength']= $request['developMuscleStrength'];
            $goal['rehabilitateInjury'] = $request['rehabilitateInjury'];
            $goal['overallFitness'] = $request['overallFitness'];
            $goal['loseBodyFat'] = $request['loseBodyFat'];
            $goal['startExerciseProgram'] = $request['startExerciseProgram'];
            $goal['designAdvanceProgram'] = $request['designAdvanceProgram'];
            $goal['increaseFlexibility'] = $request['increaseFlexibility'];
            $goal['sportsSpecificTraining'] = $request['sportsSpecificTraining'];
            $goal['increaseMuscleSize'] = $request['increaseMuscleSize'];
            $goal['cardioExercise'] = $request['cardioExercise'];
            asort($goal);
            $pendingRequests[$key]['goals'] = $goal;
        }
        echo $this->twig->render('trainer/pendingRequests.twig', array('requests'=>$pendingRequests));
    }

    public function trainerResponse()
    {
        $db = new DB();
        echo json_encode($db-> trainerResponse($_POST['id'], $_POST['trainerComments']));
//        $db-> trainerResponse($_POST['id'], $_POST['trainerComments']);

    }
    
}