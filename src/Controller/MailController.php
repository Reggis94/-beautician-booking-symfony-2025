<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class MailController extends AbstractController
{
    // public function sendMailNewAppointmentBusiness(){
    //     $messageHtml = "<h1>A client has booked the following service</h1>"; 
    // }

    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer): JsonResponse
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('regisboamah@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        // dump($mailer->send($email));
        // $objSendMail = $mailer->send($email);
        // dump(get_class($objSendMail));

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            dump($e->getMessage());
            exit;
        }

        $status = 200;
        $messageStatusOk = 'Email sent successfully';

        $messageJson = [
            'status' => $status,
            'message' => $messageStatusOk
        ];

        return new JsonResponse($messageJson, $status);;
    }

}