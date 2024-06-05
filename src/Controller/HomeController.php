<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\AdherentRepository;
use App\Repository\TypeCompanyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Jenssegers\Agent\Agent;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AdherentRepository $adherentRepository, TypeCompanyRepository $typeCompanyRepository, EventRepository $eventRepository): Response
    {

       $agent = new Agent();

        // Déterminez le type d'appareil
        // var_dump($agent->isMobile());die('test');
       

        $allRestaurants = $adherentRepository->findBy(["typeCompany" => $typeCompanyRepository->findOneBy(["name" => 'Restaurant'])]);
        $allShops = $adherentRepository->findBy(["typeCompany" => $typeCompanyRepository->findOneBy(["name" => 'Commerce'])]);
        $allCompanies = $adherentRepository->findBy(["typeCompany" => $typeCompanyRepository->findOneBy(["name" => 'Entreprise'])]);
        $allEvents = $eventRepository->findBy([ ], ['id' => 'desc']);
        $allhotels = $adherentRepository->findBy(["typeCompany" => $typeCompanyRepository->findOneBy(["name" => 'Hotel'])]);
        $allservices = $adherentRepository->findBy(["typeCompany" => $typeCompanyRepository->findOneBy(["name" => 'Service'])]);

        $elementsCount = 3;

        $events = [];
        for ($i = 0 ; $i < $elementsCount ; $i++) {
            if(!empty($allEvents[$i])) $events[] = $allEvents[$i];
        }

        $shops = [];
        for ($i = 0 ; $i < $elementsCount ; $i++) {
            if(!empty($allShops[$i])) $shops[] = $allShops[$i];
        }

        $restaurants = [];
        for ($i = 0 ; $i < $elementsCount ; $i++) {
            if(!empty($allRestaurants[$i]))  $restaurants[] = $allRestaurants[$i];
        }
        $hotels = [];
        for ($i = 0 ; $i < $elementsCount ; $i++) {
            if(!empty($allhotels[$i]))  $hotels[] = $allhotels[$i];
        }
        $services = [];
        for ($i = 0 ; $i < $elementsCount ; $i++) {
            if(!empty($allservices[$i]))  $services[] = $allservices[$i];
        }
        $companies = [];
        for ($i = 0 ; $i < $elementsCount ; $i++) {
            if(!empty($allCompanies[$i]))  $companies[] = $allCompanies[$i];
        }
        $addresses = $adherentRepository->findAll(); // Supposons que vous avez une méthode findAll dans votre repository
     
        $markers = [];
        foreach ($addresses as $address) {
            $markers[] = [
                'address' => $address->getName().' '.$address->getAddress().' '.$address->getZipcode(), // Méthode qui retourne l'adresse complète (par ex: Rue, Ville, Pays)
                'name' => $address->getName(),
                'presentation' => $address->getPresentation(),
                'link' => $address->getId()// Ajoutez d'autres données que vous voulez afficher sur la carte
            ];
        }
        
        if ($agent->isMobile()) {
            
            return $this->render('home/stadeAccueil.html.twig', [
                'controller_name' => 'HomeController',
                'restaurants' => $restaurants,
                'hotels' => $hotels,
                'services' => $services,
                'allRestaurants' => $allRestaurants,
                'shops' => $shops,
                'allShops' => $allShops,
                'companies' => $companies,
                'allCompanies' => $allCompanies,
                'events' => $events,
                'addresses' => $markers,
                'api_key' => 'AIzaSyCHS9PEOsSzuKO-sNL86DoR43vFvsAVoTA',
            ]);
        } else {
            return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
                'restaurants' => $restaurants,
                'hotels' => $hotels,
                'services' => $services,
                'allRestaurants' => $allRestaurants,
                'shops' => $shops,
                'allShops' => $allShops,
                'companies' => $companies,
                'allCompanies' => $allCompanies,
                'events' => $events,
                'addresses' => $markers,
                'api_key' => 'AIzaSyCHS9PEOsSzuKO-sNL86DoR43vFvsAVoTA',
            ]);
        }

        
    }

    /**
     * @Route("/raccourci", name="raccourci")
     */
    public function raccourci(): Response
    {
        return $this->render('home/raccourci.html.twig');
    }

    /**
     * @Route("/addresses", name="addresses")
     */
    public function addresses(AdherentRepository $adherentRepository): JsonResponse
    {
        $addresses = $adherentRepository->findAll(); // Supposons que vous avez une méthode findAll dans votre repository
        $data = [];
        foreach ($addresses as $address) {
            $data[] = [
                'address' => $address->getAddress().' 93200 Saint-Denis', // Méthode qui retourne l'adresse complète (par ex: Rue, Ville, Pays)
                'name' => $address->getName(),
                // Ajoutez d'autres données que vous voulez afficher sur la carte
            ];
        }

        return new JsonResponse($data);
    }

}