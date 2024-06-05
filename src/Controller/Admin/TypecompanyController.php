<?php

namespace App\Controller\Admin;

use App\Entity\TypeCompany;
use App\Form\TypeCompanyType;
use App\Repository\TypeCompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/type/company")
 */
class TypecompanyController extends AbstractController
{
    /**
     * @Route("/", name="admin_type_company", methods={"GET"})
     */
    public function index(TypeCompanyRepository $typeCompanyRepository): Response
    {
        return $this->render('admin/type_company/index.html.twig', [
            'type_companies' => $typeCompanyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_type_company_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $typeCompany = new TypeCompany();
        $form = $this->createForm(TypeCompanyType::class, $typeCompany);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typeCompany);
            $entityManager->flush();

            return $this->redirectToRoute('admin_type_company');
        }

        return $this->render('admin/type_company/new.html.twig', [
            'type_company' => $typeCompany,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_type_company_show", methods={"GET"})
     */
    public function show(TypeCompany $typeCompany): Response
    {
        return $this->render('admin/type_company/show.html.twig', [
            'type_company' => $typeCompany,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_type_company_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TypeCompany $typeCompany): Response
    {
        $form = $this->createForm(TypeCompanyType::class, $typeCompany);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_type_company');
        }

        return $this->render('admin/type_company/edit.html.twig', [
            'type_company' => $typeCompany,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_type_company_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TypeCompany $typeCompany): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeCompany->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($typeCompany);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_type_company');
    }
}
