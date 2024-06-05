<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Form\EventType;
use App\Form\EventTypesingle;
use App\Service\FileUploader;
use App\Repository\EventRepository;
use App\Repository\AdherentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/event")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/", name="admin_event", methods={"GET"})
     */
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('admin/event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }
    /**
     * @Route("/nosevent", name="admin_event_me", methods={"GET"})
     */
    public function nosevent(EventRepository $eventRepository,AdherentRepository $adherentRepository): Response
    {   $adherent = $adherentRepository->findOneBy(["username" =>$this->getUser()->getUsername()]);
       
       
        return $this->render('admin/event/index.html.twig', [
            'events' => $eventRepository->findBy(["adherent" => $adherent]),
        ]);
    }

    /**
     * @Route("/new", name="admin_event_new", methods={"GET","POST"})
     */
    public function new(Request $request,FileUploader $uploader,AdherentRepository $adherentRepository): Response
    {
        $event = new Event();
        if(in_array("ROLE_ADMIN",$this->getUser()->getRoles())){
            $form = $this->createForm(EventType::class, $event);
        }else{
            $form = $this->createForm(EventTypesingle::class, $event);
        }
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            $uploader->setTargetDirectory($this->getParameter('logos_directory'));
            $uploader->upload($data->getFile());
            $event->setMedia($uploader->getFileName());
            if($event->getStatus() == null){
                $event->setStatus(false);
            }
            $uploader->upload($data->getFilelogo());
            $event->setLogo($uploader->getFileName());
            if($event->getStatus() == null){
                $event->setStatus(false);
            }
            if(!in_array("ROLE_ADMIN",$this->getUser()->getRoles())){
                $adherent = $adherentRepository->findOneBy(["username" =>$this->getUser()->getUsername()]);
                $event->setAdherent($adherent);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();
          
            if(in_array("ROLE_ADMIN",$this->getUser()->getRoles())){
                return $this->redirectToRoute('admin_event');
            }else{
                return $this->redirectToRoute('admin_event_me');
            }
            
          
        }

        return $this->render('admin/event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_event_show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        return $this->render('admin/event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_event_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Event $event,FileUploader $uploader): Response
    {
        if(in_array("ROLE_ADMIN",$this->getUser()->getRoles())){
            $form = $this->createForm(EventType::class, $event);
        }else{
            $form = $this->createForm(EventTypesingle::class, $event);
        }
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $file = $data->getFile();
            $filelogo = $data->getFilelogo();
            
            if ($file != null) {
                $oldFileName = $event->getMedia();

                // upload new logo
                $uploader->setTargetDirectory($this->getParameter('logos_directory'));
                $uploader->upload($file);
                $data->setMedia($uploader->getFileName());
                
                // delete old logo from server

                $path = $this->getParameter('logos_directory') . '/' . $oldFileName;
                if (is_dir($path)) {
                    unlink($path); 
                } 
            }
            if ($filelogo != null) {
                $oldFileName = $event->getLogo();

                // upload new logo
                $uploader->setTargetDirectory($this->getParameter('logos_directory'));
                $uploader->upload($filelogo);
                $data->setLogo($uploader->getFileName());
                
                // delete old logo from server
                $path = $this->getParameter('logos_directory') . '/' . $oldFileName;
                if (is_dir($path)) {
                    unlink($path);
                } 
            }
            if($event->getStatus() == null){
                $data->setStatus(false);
            }
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
            if(in_array("ROLE_ADMIN",$this->getUser()->getRoles())){
                return $this->redirectToRoute('admin_event');
            }else{
                return $this->redirectToRoute('admin_event_me');
            }
        }

        return $this->render('admin/event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_event_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $path = $this->getParameter('logos_directory') . '/' . $event->getMedia();
            unlink($path);
            $path2 = $this->getParameter('logos_directory') . '/' . $event->getLogo();
            unlink($path2);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        if(in_array("ROLE_ADMIN",$this->getUser()->getRoles())){
            return $this->redirectToRoute('admin_event');
        }else{
            return $this->redirectToRoute('admin_event_me');
        }
    }
}
