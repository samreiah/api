<?php

namespace App\Traits;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\DataArraySerializer;

trait DataTransformer
{
    public function createData($resource)
    {
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());
        return $manager->createData($resource)->toArray();
    }
}
