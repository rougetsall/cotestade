<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Adherent;
use App\Form\AdherentType;
use App\Service\FileUploader;
use App\Service\MailerService;
use App\Service\MessageService;
use App\Repository\AdherentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/adherent")
 */
class AdherentController extends AbstractController
{
    /**
     * @Route("/", name="admin_adherent", methods={"GET"})
     */
    public function index(AdherentRepository $adherentRepository): Response
    {
        return $this->render('admin/adherent/index.html.twig', [
            'adherents' => $adherentRepository->findAll(),
        ]);
    } 
    /**
     * @Route("/login", name="admin_adherent_login", methods={"GET"})
     */
    public function login(AdherentRepository $adherentRepository): Response
    {
        return $this->render('admin/adherent/index.html.twig', [
            'adherents' => $adherentRepository->findBy(["username"=>$this->getUser()->getUsername()]),
        ]);
    }

    /**
     * @Route("/new", name="admin_adherent_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $uploader,UserPasswordEncoderInterface $passwordEncoder,MailerService $mailerService,MessageService $messageService): Response
    {
        $adherent = new Adherent();
        $form = $this->createForm(AdherentType::class, $adherent);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
         
            $user = new User();
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $data->getPassworduser()
                )
            );
            $user->setUsername($data->getUsername());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            
		  //$mailerService->send(
		  //  "identifiant Cote-stade",
		  //   "sallrouget@gmail.com",
		  //     $data->getEmail(),
		  //     'adherent/_email.html.twig',
		  //       [
		  //       "username" => $data->getUsername(),
		  //         "password" =>  $data->getPassworduser()
		  // ]
		  // );
            
            $messageService->addSuccess("Message bien envoyé");
            $uploader->setTargetDirectory($this->getParameter('logos_directory'));
            $uploader->upload($data->getFile());
            $adherent->setLogo($uploader->getFileName());

            $uploader->upload($data->getFileback());
            $adherent->setBackground($uploader->getFileName());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adherent);
            $entityManager->flush();

            return $this->redirectToRoute('admin_adherent');
        }

        return $this->render('admin/adherent/new.html.twig', [
            'adherent' => $adherent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_adherent_show", methods={"GET"})
     */
    public function show(Adherent $adherent): Response
    {
        return $this->render('admin/adherent/show.html.twig', [
            'adherent' => $adherent,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_adherent_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Adherent $adherent,FileUploader $uploader): Response
    {
        $form = $this->createForm(AdherentType::class, $adherent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
		
            $file = $data->getFile();
            $fileback = $data->getFileback();

            if ($file) {
                $oldFileName = $adherent->getLogo();

                // upload new logo
                $uploader->setTargetDirectory($this->getParameter('logos_directory'));
                $uploader->upload($file);
                $data->setLogo($uploader->getFileName());
                
                // delete old logo from server
                $path = $this->getParameter('logos_directory') . '/' . $oldFileName;
                if (is_dir($path)) {
                    unlink($path);
                } 
            }
            if ($fileback) {
                $oldFileName = $adherent->getBackground();

                // upload new logo
                $uploader->setTargetDirectory($this->getParameter('logos_directory'));
                $uploader->upload($fileback);
                $data->setBackground($uploader->getFileName());
                
                // delete old logo from server
                $path = $this->getParameter('logos_directory') . '/' . $oldFileName;
                if (is_dir($path)) {
                    unlink($path);
                } 
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
           if ($this->getUser()->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->redirectToRoute('admin_adherent');
           } else {
            return $this->redirectToRoute('admin_adherent_login');
           }
           

           
        }

        return $this->render('admin/adherent/edit.html.twig', [
            'adherent' => $adherent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="adherent_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Adherent $adherent): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adherent->getId(), $request->request->get('_token'))) {
           
            $path = $this->getParameter('logos_directory') . '/' . $adherent->getLogo();
            unlink($path);
            $path2 = $this->getParameter('logos_directory') . '/' . $adherent->getBackground();
            unlink($path2);
             $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($adherent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_adherent');
    }
}
