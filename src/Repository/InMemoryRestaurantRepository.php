<?php

namespace App\Repository;

use App\Entity\Restaurant;
use App\Entity\Address;

class InMemoryRestaurantRepository implements RestaurantRepositoryInterface
{
    public function findOneById($id): ?Restaurant
    {
        $restaurants = array_filter($this->findAll(), function(Restaurant $restaurant) use ($id) {
            return $restaurant->getId() == $id;
        });

        // Return the first restaurant only
        return reset($restaurants);
    }

    public function findAll(): array
    {
        $r1 = new Restaurant(1);
        $r1
            ->setName('Bio Burger')
            ->setLikes(5)
            ->setDislikes(1);

        $r2 = new Restaurant(2);
        $r2
            ->setName('Wok Addict')
            ->setLikes(25)
            ->setDislikes(2);

        $r3 = new Restaurant(3);
        $r3
            ->setName('231 East Street')
            ->setLikes(12)
            ->setDislikes(3);

        $addr1 = new Address();
        $addr1
            ->setStreet('10 rue de la Victoire')
            ->setZip('75009')
            ->setCity('Paris')
            ->setCountry('France');

        $addr2 = new Address();
        $addr2
            ->setStreet('91 rue de la Victoire')
            ->setZip('75009')
            ->setCity('Paris')
            ->setCountry('France');

        $addr3 = new Address();
        $addr3
            ->setStreet('2 Rue De La Pepiniere')
            ->setZip('75008')
            ->setCity('Paris')
            ->setCountry('France');

        $r1->setAddress($addr1);
        $r2->setAddress($addr2);
        $r3->setAddress($addr3);

        return [$r1, $r2, $r3];
    }
}