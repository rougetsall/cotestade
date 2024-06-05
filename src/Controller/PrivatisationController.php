<?php

namespace App\Controller;

use App\Entity\Privatisation;
use App\Form\PrivatisationType;
use App\Repository\PrivatisationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/privatisation")
 */
class PrivatisationController extends AbstractController
{
    /**
     * @Route("/", name="privatisation", methods={"GET"})
     */
    public function index(PrivatisationRepository $privatisationRepository): Response
    {
        return $this->render('privatisation/index.html.twig', [
            'privatisations' => $privatisationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="privatisation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $privatisation = new Privatisation();
        $form = $this->createForm(PrivatisationType::class, $privatisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($privatisation);
            $entityManager->flush();

            return $this->redirectToRoute('privatisation_index');
        }

        return $this->render('privatisation/new.html.twig', [
            'privatisation' => $privatisation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="privatisation_show", methods={"GET"})
     */
    public function show(Privatisation $privatisation): Response
    {
        return $this->render('privatisation/show.html.twig', [
            'privatisation' => $privatisation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="privatisation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Privatisation $privatisation): Response
    {
        $form = $this->createForm(PrivatisationType::class, $privatisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('privatisation_index');
        }

        return $this->render('privatisation/edit.html.twig', [
            'privatisation' => $privatisation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="privatisation_delete", methods={"POST"})
     */
    public function delete(Request $request, Privatisation $privatisation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$privatisation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($privatisation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('privatisation_index');
    }
}
