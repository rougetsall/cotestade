<?php

namespace App\Controller;

use DateTime;
use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Jenssegers\Agent\Agent;

class EventController extends AbstractController
{
    /**
     * @Route("/evenements", name="events_to_come", methods={"GET"})
     */
    public function index(EventRepository $eventRepository): Response
    {
        $agent = new Agent();
        $events = $eventRepository->findBy(["status" => "1"]);
        $dateindex = new DateTime();
        $data = [];
        foreach ($events as $event) {
            if ((strtotime($event->getDate()->format('Y-m-d H:i:s'))) - (strtotime($dateindex->format('Y-m-d H:i:s'))) > 0) {
                $data[] = $event;
            }
        }
        
        $shops = $data;
        if ($agent->isMobile()) {
            return $this->render('event/showmobile.html.twig', [
                'shops' => $shops,
                'titrecom' => "Prochains événements",
            ]);
        }else{
            return $this->render('event/events.html.twig', [
                'events' => $data,
            ]);

        }

        
    }

    /**
     * @Route("/evenements/bonplan", name="events_to_bonplan", methods={"GET"})
     */
    public function bonplan(EventRepository $eventRepository): Response
    {
        $agent = new Agent();

        $events = $eventRepository->findBy(["status" => "1","category" => "2"]);
        $dateindex = new DateTime();
        $data = [];
        foreach ($events as $event) {
            if ((strtotime($event->getDate()->format('Y-m-d H:i:s'))) - (strtotime($dateindex->format('Y-m-d H:i:s'))) > 0) {
                $data[] = $event;
            }
        }

        $shops = $data;
        if ($agent->isMobile()) {
           // dd($shops);
            return $this->render('event/bplmobile.html.twig', [
                'shops' => $shops,
                'titrecom' => "Bons plans du moment",
            ]);
        }else{
            return $this->render('event/events.html.twig', [
                'events' => $data,
            ]);

        }

       
    }
    /**
     * @Route("/evenements/promos", name="events_to_promos", methods={"GET"})
     */
    public function promos(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findBy(["status" => "1","category" => "3"]);
        $dateindex = new DateTime();
        $data = [];
        foreach ($events as $event) {
            if ((strtotime($event->getDate()->format('Y-m-d H:i:s'))) - (strtotime($dateindex->format('Y-m-d H:i:s'))) > 0) {
                $data[] = $event;
            }
        }

        return $this->render('event/events.html.twig', [
            'events' => $data,
        ]);
    }
    
    /**
     * @Route("/evenement/passes", name="events_happened", methods={"GET"})
     */
    public function happened(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findBy(["status" => "1"]);
        $dateindex = new DateTime();
        $donnes = [];
        foreach ($events as $event) {
            if ((strtotime($event->getDate()->format('Y-m-d H:i:s'))) - (strtotime($dateindex->format('Y-m-d H:i:s'))) < 0) {
                $donnes [] = $event;
            }

        }
        return $this->render('event/index.html.twig', [
            'events' => $donnes,
        ]);
    }

    /**
     * @Route("evenements/tous", name="events_index", methods={"GET"})
     */
    public function events(): Response
    {
        return $this->render('event/events.html.twig');
    }


    /**
     * @Route("evenement/{id}", name="event_show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }


}
