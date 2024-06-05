<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Form\AdherentType;
use App\Repository\MediaRepository;
use App\Repository\AdherentRepository;
use App\Repository\TypeCompanyRepository;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Jenssegers\Agent\Agent;

//@Route("/adherent")

class AdherentController extends AbstractController
{
    /**
     * @Route("/adherents-menu", name="adherents_menu", methods={"GET"})
     */
    public function menu(TypeCompanyRepository $typeCompanyRepository): Response
    {
        return $this->render('adherent/menu.html.twig', [
            'categories' => $typeCompanyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/commerces", name="shops", methods={"GET"})
     */
    public function commerces(AdherentRepository $adherentRepository, TypeCompanyRepository $repositoryCompany): Response
    {

        $agent = new Agent();
        $commerceCategory = $repositoryCompany->findOneBy(['name' => 'Commerce']);

        $shops = $adherentRepository->findBy(["typeCompany" => $commerceCategory->getId()]);
        if ($agent->isMobile()) {
            return $this->render('adherent/commercesmobile.html.twig', [
                'shops' => $shops,
                'titrecom' => "Commerces",
            ]);
        }else{
            return $this->render('adherent/commerces.html.twig', [
                'shops' => $shops
            ]);

        }
       
    }

    /**
     * @Route("/hotels", name="hotels", methods={"GET"})
     */
    public function hotels(AdherentRepository $adherentRepository, TypeCompanyRepository $repositoryCompany): Response
    {
        $agent = new Agent();
        $commerceCategory = $repositoryCompany->findOneBy(['name' => 'Hotel']);

        $hotels = $adherentRepository->findBy(["typeCompany" => $commerceCategory->getId()]);
        $shops = $adherentRepository->findBy(["typeCompany" => $commerceCategory->getId()]);
        if ($agent->isMobile()) {
            return $this->render('adherent/commercesmobile.html.twig', [
                'shops' => $shops,
                'titrecom' => "Hotels",
            ]);
        }else{
            return $this->render('adherent/hotels.html.twig', [
                'hotels' => $hotels
            ]);

        }
       
    }

    /**
     * @Route("/restaurants", name="restaurants", methods={"GET"})
     */
    public function restaurants(AdherentRepository $adherentRepository, TypeCompanyRepository $repositoryCompany): Response
    {
        $agent = new Agent();
        $restaurantCategory = $repositoryCompany->findOneBy(['name' => 'Restaurant']);

        $restaurants = $adherentRepository->findBy(["typeCompany" => $restaurantCategory->getId()]);
        $shops = $adherentRepository->findBy(["typeCompany" => $restaurantCategory->getId()]);
        if ($agent->isMobile()) {
            return $this->render('adherent/commercesmobile.html.twig', [
                'shops' => $shops,
                'titrecom' => "Restaurants",
            ]);
        }else{
            return $this->render('adherent/restaurants.html.twig', [
                'restaurants' => $restaurants
            ]);

        }
       
        
    }

    /**
     * @Route("/entreprises", name="companies", methods={"GET"})
     */
    public function companies(AdherentRepository $adherentRepository, TypeCompanyRepository $repositoryCompany): Response
    {
        $agent = new Agent();
        $companyCategory = $repositoryCompany->findOneBy(['name' => 'Service']);

        $companies = $adherentRepository->findBy(["typeCompany" => $companyCategory->getId()]);
        $shops = $adherentRepository->findBy(["typeCompany" => $companyCategory->getId()]);
        if ($agent->isMobile()) {
            return $this->render('adherent/commercesmobile.html.twig', [
                'shops' => $shops,
                'titrecom' => "Entreprises",
            ]);
        }else{
            return $this->render('adherent/companies.html.twig', [
                'companies' => $companies
            ]);

        }
      
    }

    /**
     * @Route("/adherents", name="adherents", methods={"GET"})
     */
    public function index(AdherentRepository $adherentRepository): Response
    {
        return $this->render('adherent/index.html.twig', [
            'adherents' => $adherentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="adherent_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $adherent = new Adherent();
        $form = $this->createForm(AdherentType::class, $adherent);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adherent);
            $entityManager->flush();

            return $this->redirectToRoute('adherent_index');
        }

        return $this->render('adherent/new.html.twig', [
            'adherent' => $adherent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/adherent/{id}", name="adherent_show", methods={"GET"})
     */
    public function show(Adherent $adherent, MediaRepository $mediaRepository): Response
    {
        return $this->render('adherent/show.html.twig', [
            'adherent' => $adherent,
            'galeries' => $mediaRepository->findBy(["user" => $adherent->getId()]),
        ]);
    }

    /**
     * @Route("/adherent/edit/{id}", name="adherent_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Adherent $adherent): Response
    {
        $form = $this->createForm(AdherentType::class, $adherent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('adherent_index');
        }

        return $this->render('adherent/edit.html.twig', [
            'adherent' => $adherent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/adherent/delete/{id}", name="adherent_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Adherent $adherent): Response
    {
        if ($this->isCsrfTokenValid('delete' . $adherent->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($adherent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('adherent_index');
    }
}
