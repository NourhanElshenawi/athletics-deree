<?php
namespace Nourhan\Controllers;

use Nourhan\Database\DB;
use Nourhan\Services\Upload;
use Nourhan\ReCaptcha;


class NurseController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function seePendingCertificates()
    {
        $db = new DB();

        $users = $db->getUsers();

        echo $this->twig->render('nurse/pendingCertificates.twig', array('users'=>$users));
    }

    public function seeApprovedCertificates()
    {
        echo "see approved certificates";
    }

    public function seeRejectedCertificates()
    {
        echo "see rejected certificates";
    }



}