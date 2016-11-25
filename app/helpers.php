<?php

/**
 *
 * Retrieve part of string
 *
 **/
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