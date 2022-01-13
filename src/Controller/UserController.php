<?php

namespace App\Controller;

use App\Form\UserType;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route ("/admin/users", requirements={})
     */
    public function adminListUser()
    {
        return new Response('Admin user listing');
    }

    /**
     * @Route ("/api/users", requirements={})
     */
    public function apiListUser()
    {
        return new Response('Api user listing');
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserManager $userManager): Response
    {
        $form = $this->createForm(UserType::class, null, [
            'validation_groups' => ['CREATE', 'Default'],
        ]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid() && $form->get('cgu')->getData())
        {
            $user = $form->getData();
            $userManager->register($user);
            dump($user);
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
