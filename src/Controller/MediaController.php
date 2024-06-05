<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\MediaType;
use App\Service\FileUploader;
use App\Repository\MediaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/media")
 */
class MediaController extends AbstractController
{
    /**
     * @Route("/", name="media_index", methods={"GET"})
     */
    public function index(MediaRepository $mediaRepository): Response
    {
        return $this->render('media/index.html.twig', [
            'media' => $mediaRepository->findAll(),
        ]);
    }

   

   

    
}
