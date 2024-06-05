<?php

namespace App\Controller;

use App\Entity\Recrutement;
use App\Form\RecrutementType;
use App\Repository\RecrutementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recrutement")
 */
class RecrutementController extends AbstractController
{
    /**
     * @Route("/", name="recrutement", methods={"GET"})
     */
    public function index(RecrutementRepository $recrutementRepository): Response
    {
        return $this->render('recrutement/index.html.twig', [
            'recrutements' => $recrutementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="recrutement_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $recrutement = new Recrutement();
        $form = $this->createForm(RecrutementType::class, $recrutement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($this->getUser());
            $recrutement->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recrutement);
            $entityManager->flush();

            return $this->redirectToRoute('recrutement_index');
        }

        return $this->render('recrutement/new.html.twig', [
            'recrutement' => $recrutement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recrutement_show", methods={"GET"})
     */
    public function show(Recrutement $recrutement): Response
    {
        return $this->render('recrutement/show.html.twig', [
            'recrutement' => $recrutement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="recrutement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Recrutement $recrutement): Response
    {
        $form = $this->createForm(RecrutementType::class, $recrutement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recrutement_index');
        }

        return $this->render('recrutement/edit.html.twig', [
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

        return $this->redirectToRoute('recrutement_index');
    }
}
