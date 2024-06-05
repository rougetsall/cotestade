<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\MailerService;
use App\Service\MessageService;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Datetime;
/**
 * @Route("/contact")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact", methods={"GET","POST"})
     */
    public function add(Request $request,MailerService $mailerService,MessageService $messageService): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setSendAt(new Datetime);
           
            $data = $form->getData();
            $mailerService->send(
                "Prise de contact de la part de " . $data->getName(),
                $data->getEmail(),
                "makeupbouque@gmail.com",
                'contact/_email.html.twig',
                [
                    "name" => $data->getName(),
                    "email" => $data->getEmail(),
                    "message" => $data->getMessage()
                ]
            );
            
            $messageService->addSuccess("Message bien envoyé");

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();
            
            return $this->redirectToRoute('home');
        }

        return $this->render('contact/index.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }
}
