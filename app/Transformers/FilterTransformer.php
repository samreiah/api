<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\Filter;
//use App\Transformers\ManufacturerTransformer;

class FilterTransformer extends TransformerAbstract
{


    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(Filter $filter)
  	{
  	    return [
            'filter_id'   		=> $filter->id,
            'filter_group_id' 	=> $filter->filter_group_id,
            'filter_name'     	=> $filter->name,
  	    ];
  	}
}
