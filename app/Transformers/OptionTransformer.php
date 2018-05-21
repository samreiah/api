<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\Option;
use App\Transformers\OptionValueTransformer;

class OptionTransformer extends TransformerAbstract
{
	
	protected $availableIncludes = [
        'optionValue',
    ];

    protected $defaultIncludes = [
        'optionValue',
    ];

    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(Option $option)
  	{
  	    return [
            'option_id'   		=> $option->id,
			'option_type'   	=> $option->type,
  	    ];
  	}
	
	public function includeOptionValue(Option $option) {
		$optionvalues = $option->values;
		if($optionvalues == null) {
			return null;
		} 
		
		return $this->Collection($optionvalues, new OptionValueTransformer);
	}
}
