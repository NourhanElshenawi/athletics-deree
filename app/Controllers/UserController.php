<?php
/**
 * Created by PhpStorm.
 * User: antony
 * Date: 11/17/16
 * Time: 4:37 PM
 */

namespace Nourhan\Controllers;


use Nourhan\Database\DB;

class UserController extends Controller
{
    public function requestProgram ()
    {
        echo $this->twig->render('customer/requestProgram.twig');
    }

    public function submitProgram ()
    {
        d($_POST);

        $db = new DB();

        $db->createProgramRequest($_POST);
    }
}