<?php

namespace App\Repository;

interface RestaurantRepositoryInterface
{
    public function findOneById($id);

    public function findAll();

}