<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\ProductOption;
use App\Transformers\OptionTransformer;

class ProductOptionTransformer extends TransformerAbstract
{
	
	protected $availableIncludes = [
        'option',
    ];

    protected $defaultIncludes = [
        'option',
    ];

    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(ProductOption $productoption)
  	{
  	    return [
            'product_option_id'   		=> $productoption->id,
  	    ];
  	}
	
	public function includeOption(ProductOption $productoption) {
		$option = $productoption->option;
		if($option == null) {
			return null;
		}
		
		return $this->Item($option, new OptionTransformer);
	}
}
