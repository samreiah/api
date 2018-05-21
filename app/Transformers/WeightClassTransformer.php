<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\WeightClass;
//use App\Transformers\ManufacturerTransformer;

class WeightClassTransformer extends TransformerAbstract
{


    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(WeightClass $WeightClass)
  	{
  	    return [
            'title'   	=> $WeightClass->title,
            'unit' 		=> $WeightClass->unit,
            'value'     => $WeightClass->value,
  	    ];
  	}
}
