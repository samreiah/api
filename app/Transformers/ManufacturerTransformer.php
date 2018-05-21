<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\Manufacturer;
//use App\Transformers\ManufacturerTransformer;

class ManufacturerTransformer extends TransformerAbstract
{


    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(Manufacturer $manufacturer)
  	{
  	    return [
            'manufacturer_id'   => $manufacturer->id,
            'manufacturer_name' => $manufacturer->name,
            'slug'             	=> $manufacturer->slug,
            'image'      		=> $manufacturer->image,
  	    ];
  	}
}
