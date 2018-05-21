<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\OptionValue;

class OptionValueTransformer extends TransformerAbstract
{
	
    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(OptionValue $optionvalue)
  	{
  	    return [
            'option_value_id'   => $optionvalue->id,
			'value_type'   	=> $optionvalue->type,
  	    ];
  	}
}
