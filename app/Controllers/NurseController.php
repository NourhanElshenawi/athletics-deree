<?php
namespace Nourhan\Controllers;

use Nourhan\Database\DB;
use Nourhan\Services\SwiftMailer;


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
        $db = new DB();

        $users = $db->getApprovedCertificates();

        echo $this->twig->render('nurse/approvedCertificates.twig', array('users'=>$users));
    }

    public function seeRejectedCertificates()
    {
        $db = new DB();

        $users = $db->getRejectedCertificates();

        echo $this->twig->render('nurse/rejectedCertificates.twig', array('users'=>$users));
    }

    public function approveCertificate()
    {
        $db = new DB();

        $result = $db->approveUserCertificate($_POST['user_certificate_id']);
        if($result){
                $mailer = new SwiftMailer();
                $result = $mailer->sendEmail($_POST);
        }

        echo json_encode($result);
    }

    public function rejectCertificate()
    {
        $db = new DB();

        $result = $db->rejectUserCertificate($_POST['user_certificate_id']);
        if ($result){
            $mailer = new SwiftMailer();
            $result = $mailer->sendEmail($_POST);
        }
        echo json_encode($result);
    }

}