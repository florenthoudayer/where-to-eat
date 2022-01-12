<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, null, [
            'validation_groups' => ['CREATE', 'Default'],
        ]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid() && $form->get('cgu')->getData())
        {
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();
            dump($user);
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
