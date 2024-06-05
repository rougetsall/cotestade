<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/users", name="admin_user", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="show_user", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/edit/{id}", name="edit_user", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $passwoed = $user->getPassword();
        $user->setPassword("new password si vous avez oublie");
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if($user->getPassword() !="new password si vous avez oublie" ){
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $user->getPassword() 
                    )
                );
            }else{
                $user->setPassword($passwoed);
            }
            if ($data->getRole() == "ROLE_ADMIN") {
                $roles[] = $data->getRole();
                $user->setRoles($roles);
            } else {
                $roles = [];
                $user->setRoles($roles);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_user');
        }
       
        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/delete/{id}", name="delete_user", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user');
    }
}