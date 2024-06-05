<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Form\MediaType;
use App\Service\FileUploader;
use App\Repository\MediaRepository;
use App\Repository\AdherentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/media")
 */
class MediaController extends AbstractController
{
    /**
     * @Route("/", name="admin_media", methods={"GET"})
     */
    public function index(MediaRepository $mediaRepository,AdherentRepository $adherentRepository): Response
    {
        if ($this->getUser()->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->render('admin/media/index.html.twig', [
                'media' => $mediaRepository->findAll(),
            ]);
           } else {
            $adherent =  $adherentRepository->findOneBy(["username"=>$this->getUser()->getUsername()]);
            return $this->render('admin/media/index.html.twig', [
                'media' => $mediaRepository->findBy(["user"=>$adherent->getId()]),
            ]);
           
        }

    }

    /**
     * @Route("/new", name="media_new", methods={"GET","POST"})
     */
    public function new(Request $request,FileUploader $uploader ,AdherentRepository $adherentRepository): Response
    {
        $media = new Media();
        $form = $this->createForm(MediaType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $adherend =  $adherentRepository->findOneBy(["username"=>$this->getUser()->getUsername()]);
            $uploader->setTargetDirectory($this->getParameter('logos_directory'));
            $uploader->upload($data->getFile());
            $media ->setFiles($uploader->getFileName());
            $media ->setUser($adherend);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($media);
            $entityManager->flush();

            return $this->redirectToRoute('admin_media');
        }
        return $this->render('admin/media/new.html.twig', [
            'medium' => $media,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="media_show", methods={"GET"})
     */
    public function show(Media $medium): Response
    {
        return $this->render('admin/media/show.html.twig', [
            'media' => $medium,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="media_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Media $medium): Response
    {
        $form = $this->createForm(MediaType::class, $medium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_media');
        }

        return $this->render('admin/media/edit.html.twig', [
            'media' => $medium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="media_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Media $medium): Response
    {
        if ($this->isCsrfTokenValid('delete'.$medium->getId(), $request->request->get('_token'))) {
            $path = $this->getParameter('logos_directory') . '/' . $medium->getFiles();
            unlink($path);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($medium);
            $entityManager->flush();
        }

        return $this->redirectToRoute('media_index');
    }
}
