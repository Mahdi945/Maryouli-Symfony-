<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact", methods={"GET", "POST"})
     */
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $email = $request->request->get('email');
            $subject = $request->request->get('subject');
            $messageContent = $request->request->get('message');

            // Création du message email
            $emailMessage = (new Email())
                ->from($email)
                ->to('mahdibeyy@gmail.com') 
                ->subject($subject)
                ->html('<p><strong>Nom:</strong> ' . $name . '</p>
                        <p><strong>Email:</strong> ' . $email . '</p>
                        <p><strong>Message:</strong><br>' . nl2br($messageContent) . '</p>');

            // Envoi de l'email
            $mailer->send($emailMessage);

            // Message de confirmation et redirection
            $this->addFlash('success', 'Votre message a été envoyé avec succès.');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig');
    }
}