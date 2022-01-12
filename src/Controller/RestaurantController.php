<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Repository\InMemoryRestaurantRepository;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     */
    public function new(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $address = new Address();
        $address->setCity('Le Mans')->setZip(72000)->setCountry('France');
        $restaurant = new Restaurant();
        $restaurant->setAddress($address);

        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($restaurant);
            $entityManager->flush();

            dump($restaurant);
        }

        return $this->render('restaurant/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
