<?php
/**
 * Created by PhpStorm.
 * User: antony
 * Date: 11/17/16
 * Time: 4:37 PM
 */

namespace Nourhan\Controllers;


class UserController extends Controller
{
    public function requestProgram ()
    {
        echo $this->twig->render('customer/requestProgram.twig');
    }

    public function submitProgram ()
    {
        ddd($_POST);
    }
}