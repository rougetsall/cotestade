<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Adherent;
use App\Repository\AdherentRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


/**
 * @Route("/api")
 */
class ApiController
{
    

    /**
     * @Route("/adherent")
     */
    public function getAdherent(AdherentRepository $adherentRepository,Request $request): JsonResponse
    {
        $donnees = $adherentRepository->findBy([],['id' => 'desc']);
        

        $response = $this->adressCollectionToArray($donnees);
        return new JsonResponse($response, 200);
    }
    /**
     * This function convert a Product Collection to an array
     */
    private function adressCollectionToArray($collection): array
    {
        $response = [];
        
        foreach ($collection as $adress) {
           
            $response[] = [
                'id' => $adress->getId(),
                'title' => $adress->getName(),
                'adress' => $adress->getAddress(),
                'zipcode' => $adress->getzipcode(),
                'town' => $adress->getTown(),
            ];
            
        }

        return $response;
    }

}