<?php
/**
 * Created by PhpStorm.
 * User: nourhanelshenawi
 * Date: 12/8/16
 * Time: 10:06 PM
 */

namespace Nourhan\Services;

use Swift_Mailer;
use Swift_Message;
use Swift_RfcComplianceException;
use Swift_SmtpTransport;
class SwiftMailer
{
    public function __construct()
    {
    }
    public function sendEmail($data)
    {
        $result = array();
        //Check if $_POST is empty and sanitize
        $name = $data['name'];
        $email = $data['email'];
        $message = $data['message'];
//        $subject = $data['subject'];
//        $message = $data['message'];
//        if(!empty($name) && !empty($email) && !empty($message) && !empty($subject)) {
        if(!empty($email)) {
            $cleanName = filter_var($name, FILTER_SANITIZE_STRING);
            $cleanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            $cleanMessage = filter_var($message, FILTER_SANITIZE_STRING);
            $cleanSubject = filter_var("Subject", FILTER_SANITIZE_STRING);
        } else {
            $result['success'] = 0;
            $result['message'] = "Error: You need to fill in all the fields.";
            return $result;
        }
        // Create the Transport
        $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
            ->setUsername(getenv('MAIL'))
//            ->setUsername("nourhanelshenawy@gmail.com")
            ->setPassword(getenv('MAIL_PASS'))
//            ->setPassword("supernatural8201")
        ;
        // Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);
        try {
            // Create the message
            $message = Swift_Message::newInstance()
                // Give the message a subject
                ->setSubject('DEREE-Athletics.gr: ' . $cleanSubject)
                // Set the From address with an associative array
//                ->setTo(array($cleanEmail => $cleanName))
                ->setTo($cleanEmail)
                // Set the To addresses with an associative array
                ->setFrom('athletics.deree@gmail.com')
                // Give it a body
                ->setBody("Dear ". $cleanName. ",\n \n". $cleanMessage . "\n \n Best, \n Deree Athletics");
            // Optionally add any attachments
//			->attach(Swift_Attachment::fromPath('my-document.pdf'))
            // Send the message
            /**
             * @var \Swift_Mime_Message $message
             */
            $messageSent = $mailer->send($message);
        } catch (Swift_RfcComplianceException $e ) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
            return $result;
        }
        //Confirm Email Sent
        if ($messageSent > 0){
            $result['success'] = true;
            return $result;
        } else {
            $result['success'] = false;
            $result['message'] = "Error: We couldn't send a notification email to the user. \n Please contact support.";
            return $result;
        }
    }
    public function sendEmailToSupport($data)
    {
        $result = array();
        //Check if $_POST is empty and sanitize
        $mobile = $data['mobile'];
        $subject = $data['subject'];
        $message = $data['message'];
        if(!empty($mobile) && !empty($message) && !empty($subject)) {
            $cleanMessage = filter_var($message, FILTER_SANITIZE_STRING);
            $cleanSubject = filter_var($subject, FILTER_SANITIZE_STRING);
        } else {
            $result['success'] = 0;
            $result['message'] = "Error: You need to fill in all the fields.";
            return $result;
        }
        // Create the Transport
        $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
            ->setUsername(getenv('MAIL'))
            ->setPassword(getenv('GMAIL_PASS'))
        ;
        // Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);
        try {
            // Create the message
            $message = Swift_Message::newInstance()
                // Give the message a subject
                ->setSubject('Email from fabgraphics.gr: ' . $cleanSubject)
                // Set the From address with an associative array
                ->setFrom(array('fab.agia@gmail.com' => 'Nikos Davrazos'))
                // Set the To addresses with an associative array
                ->setTo(array('support@codeburrow.com' => 'CodeBurrow Support Team'))
                // Give it a body
                ->setBody($cleanMessage . "\n\nMobile Number: " . $mobile);
            // Optionally add any attachments
//			->attach(Swift_Attachment::fromPath('my-document.pdf'))
            // Send the message
            /**
             * @var \Swift_Mime_Message $message
             */
            $messageSent = $mailer->send($message);
        } catch (Swift_RfcComplianceException $e ) {
            $result['success'] = 0;
            $result['message'] = $e->getMessage();
            return $result;
        }
        //Confirm Email Sent
        if ($messageSent > 0){
            $result['success'] = 1;
            $result['message'] = "Thank you for your email.\n We'll be in touch soon.";
            return $result;
        } else {
            $result['success'] = 0;
            $result['message'] = "Error: We couldn't send your email. \n Please contact us at 'support@codeburrow.com'.";
            return $result;
        }
    }
}