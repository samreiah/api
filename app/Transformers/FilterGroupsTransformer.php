<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\FilterGroup;
use App\Transformers\FilterTransformer;

class FilterGroupsTransformer extends TransformerAbstract
{
	
	protected $availableIncludes = [
        'filters',
    ];

    protected $defaultIncludes = [
        'filters',
    ];

    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(FilterGroup $filtergroup)
  	{
  	    return [
            'filter_group_id'   		=> $filtergroup->id,
			'name'   					=> $filtergroup->name,
			'input_type'				=> $filtergroup->input_type,
  	    ];
  	}
	
	public function includeFilters(FilterGroup $filtergroup) {
		$filters = $filtergroup->filters;
		if($filters == null) {
			return null;
		} 
		
		return $this->Collection($filters, new FilterTransformer);
	}
}
