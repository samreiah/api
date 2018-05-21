<?php

namespace App\Traits;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

trait Serializer
{
    

     /**
     * [createData description]
     * @param  [type] $resource [description]
     * @return [type]           [description]
     */
    public function createData($resource)
    {
        return $this->convertResource($resource);
    }

    /**
     * [createPaginatedData description]
     * @param  [type] $paginator   [description]
     * @param  [type] $transformer [description]
     * @return [type]              [description]
     */
    public function createPaginatedData($paginator, $transformer)
    {
        $resource = new Collection($paginator->getCollection(), $transformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return $this->convertResource($resource);
    }
	
	/**
     * [createCollectionData description]
     * @param  [type] $paginator   [description]
     * @param  [type] $transformer [description]
     * @return [type]              [description]
     */
	 public function createCollectionData($resource, $transformer)
    {
        $resource = new Collection($resource, $transformer);
        return $this->convertResource($resource);
    }
	

    /**
     * [convertResource description]
     * @param  [type] $resource [description]
     * @return [type]           [description]
     */
    public function convertResource($resource)
    {
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());
        return $manager->createData($resource)->toArray();
    }
}
