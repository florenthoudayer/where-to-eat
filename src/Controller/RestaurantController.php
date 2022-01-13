<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * GET /restaurant
 * GET /restaurant/{id}
 * POST /restaurant
 * PUT /restaurant/{id}
 *
 * @Route("", name="restaurant_", methods={"GET"})
 */
class RestaurantController extends AbstractController
{
    private $restaurantRepository;

    public function __construct(RestaurantRepository $restaurantRepository)
    {
        $this->restaurantRepository = $restaurantRepository;
        //$this->restaurantRepository = new InMemoryRestaurantRepository();
    }

    /**
     * @Route("/", name="list")
     */
    public function index(): Response
    {
        $restaurants = $this->restaurantRepository->findAll();

        return $this->render('restaurant/index.html.twig', [
            'restaurants' => $restaurants
        ]);
    }

    /**
     * @Route("/restaurant/{id}", name="show", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        $restaurant = $this->restaurantRepository->findOneById($id);

        return $this->render('restaurant/show.html.twig', [
            'restaurant' => $restaurant
        ]);
    }

    /**
     * @Route ("/restaurant/new", name="new", methods={"POST"})
     * @isGranted("ROLE_ADMIN", message="You should have the ROLE_ADMIN to add new Restaurant")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security, EventDispatcherInterface $eventDispatcher) : Response
    {
        $address = new Address();
        $address->setCity('Le Mans')->setZip(72000)->setCountry('France');
        $restaurant = new Restaurant();
        $restaurant->setAddress($address);

        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $restaurant->setUser($security->getUser());
            $entityManager->persist($restaurant);
            $entityManager->flush();

            $eventDispatcher->addListener('kernel.terminate', function ()
            {
                //task 1: Send 100 emails to all users
                //task 2: Resize of restaurant photo
                sleep(5);
            });

            dump($restaurant);
        }

        return $this->render('restaurant/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
