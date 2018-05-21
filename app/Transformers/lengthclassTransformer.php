<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\LengthClass;
//use App\Transformers\ManufacturerTransformer;

class lengthclassTransformer extends TransformerAbstract
{


    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(LengthClass $LengthClass)
  	{
  	    return [
            'title'   	=> $LengthClass->title,
            'unit' 		=> $LengthClass->unit,
            'value'     => $LengthClass->value,
  	    ];
  	}
}
