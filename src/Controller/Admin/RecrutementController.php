<?php

namespace App\Controller\Admin;

use App\Entity\Recrutement;
use App\Form\RecrutementType;
use App\Repository\RecrutementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/recrutement")
 */
class RecrutementController extends AbstractController
{
    /**
     * @Route("/", name="admin_recrutement", methods={"GET"})
     */
    public function index(RecrutementRepository $recrutementRepository): Response
    {
        return $this->render('admin/recrutement/index.html.twig', [
            'recrutements' => $recrutementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/no-recrutement", name="admin_recrutement_me", methods={"GET"})
     */
    public function nosrecrutement(RecrutementRepository $recrutementRepository): Response
    {   
       
        return $this->render('admin/recrutement/index.html.twig', [
            'recrutements' => $recrutementRepository->findBy(["user_id" => $this->getUser()->getId()]),
        ]);
    }

    /**
     * @Route("/new", name="admin_recrutement_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $recrutement = new Recrutement();
        $form = $this->createForm(RecrutementType::class, $recrutement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recrutement->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recrutement);
            $entityManager->flush();

            return $this->redirectToRoute('admin_recrutement');
        }

        return $this->render('admin/recrutement/new.html.twig', [
            'recrutement' => $recrutement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_recrutement_show", methods={"GET"})
     */
    public function show(Recrutement $recrutement): Response
    {
        return $this->render('admin/recrutement/show.html.twig', [
            'recrutement' => $recrutement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_recrutement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Recrutement $recrutement): Response
    {
        $form = $this->createForm(RecrutementType::class, $recrutement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_recrutement');
        }

        return $this->render('admin/recrutement/edit.html.twig', [
            'recrutement' => $recrutement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recrutement_delete", methods={"POST"})
     */
    public function delete(Request $request, Recrutement $recrutement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recrutement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($recrutement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_recrutement');
    }
}
