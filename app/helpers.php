<?php

/**
 *
 * Retrieve part of string
 *
 **/
use Nourhan\Database\DB;

function after ($this, $inthat)
{
	if (!is_bool(strpos($inthat, $this)))
	{
		return substr($inthat, strpos($inthat, $this) + strlen($this));
	}

	return false;
}

function before ($this, $inthat)
{
	return substr($inthat, 0, strpos($inthat, $this));
}

function between ($this, $that, $inthat)
{
    return before($that, after($this, $inthat));
}

function redirect($url, $statusCode = 303)
{
    header('Location: ' . $url, true, $statusCode);
    die();
}

function beautifyClassesForCalendar($classes) {
    foreach ($classes as $key=>$class) {

        $class['days']=array();

        if($class['monday']){
            $classes[$key]['days'][]="1";
        }
        if($class['tuesday']){
            $classes[$key]['days'][]="2";
        }
        if($class['wednesday']){
            $classes[$key]['days'][]="3";
        }
        if($class['thursday']){
            $classes[$key]['days'][]="4";
        }
        if($class['friday']){
            $classes[$key]['days'][]="5";
        }
    }

    return $classes;
}


function beautifyClasses($classes){

    $db = new DB();

    foreach ($classes as $key=>$class ){

        $temp = ($class['currentCapacity']*100)/$class['capacity'];
        $temp2 = $class['currentCapacity'];

        $classes[$key]['currentCapacityPercentage'] = $temp;
        $classes[$key]['currentCapacity'] = $temp2;

        $classes[$key]['users'] = $db->getRegisteredUsers($class['id']);

        $class['days']=array();

        if($class['monday']){
            $classes[$key]['days']['monday']="1";
//                $classes[$key]['days'][]="monday";
//                echo $class['monday'];
        } else {
            $classes[$key]['days']['monday']="0";
        }
        if($class['tuesday']){
            $classes[$key]['days']['tuesday']="1";
//                $classes[$key]['days'][]="tuesday";
        }else {
            $classes[$key]['days']['tuesday']="0";
        }
        if($class['wednesday']){
            $classes[$key]['days']['wednesday']="1";
//                $classes[$key]['days'][]="wednesday";
        }else {
            $classes[$key]['days']['wednesday']="0";
        }
        if($class['thursday']){
            $classes[$key]['days']['thursday']="1";
//                $classes[$key]['days'][]="thursday";
        }else {
            $classes[$key]['days']['thursday']="0";
        }
        if($class['friday']){
            $classes[$key]['days']['friday']="1";
//                $classes[$key]['days'][]="friday";
        }else {
            $classes[$key]['days']['friday']="0";
        }
    }

    return $classes;
}

function convertGoalsList($programRequests){
    foreach ($programRequests as $key=>$request){
        $goal = array();
        $goal['develop Muscle Strength']= $request['developMuscleStrength'];
        $goal['rehabilitate Injury'] = $request['rehabilitateInjury'];
        $goal['overall Fitness'] = $request['overallFitness'];
        $goal['loseBody Fat'] = $request['loseBodyFat'];
        $goal['start Exercise Program'] = $request['startExerciseProgram'];
        $goal['design Advance Program'] = $request['designAdvanceProgram'];
        $goal['increase Flexibility'] = $request['increaseFlexibility'];
        $goal['sports Specific Training'] = $request['sportsSpecificTraining'];
        $goal['increase MuscleSize'] = $request['increaseMuscleSize'];
        $goal['cardio Exercise'] = $request['cardioExercise'];
        asort($goal);
        $programRequests[$key]['goals'] = $goal;
    }
    return $programRequests;
}

function convertJoinDBReturns($array){
    $arrayReturn = array();
    foreach ($array as $arr){
        foreach ($arr as $r){
            $array[]= $r;
        }
    }

    return $arrayReturn;
}

function daysDateDifference($date1, $date2){
    $days = date_diff(date_create($date1), date_create($date2))->days;

    return $days;
}
function minutesTimeDifference($date1, $date2){
    $diff = date_diff(date_create($date1), date_create($date2));

    return $diff;
}

function avgLogTime($logTimes){

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

    $totalMins = array_sum($minutes);
    $avgMins = $totalMins/count($minutes);

    $totalSecs = array_sum($seconds);
    $avgSecs = $totalSecs/count($seconds);

    $avg = array(
        'hours' => $avgHours,
        'mins' => $avgMins,
        'secs' => $avgSecs
    );

    return $avg;
}