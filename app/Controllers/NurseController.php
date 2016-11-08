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

        $users = $db->getUserCertificates();

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

    public function approveCertificate()
    {
        $db = new DB();

        $result = $db->approveUserCertificate($_POST['user_certificate_id']);

        echo json_encode($result);
    }

}