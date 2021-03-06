<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\Category;

class ChildrenRecursiveCategoryTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'children'
    ];

    protected $defaultIncludes = [
        'children'
    ];

    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(Category $category)
  	{
  	    return [
            'category_id'     	=> $category->id,
            'category_name'     => $category->name,
            'description'       => $category->description,
            'image'      		=> $category->image,
			'slug'				=> $category->slug,
            'meta_title'        => $category->meta_title,
            'meta_description'  => $category->meta_description,
            'meta_keyword'      => $category->meta_keyword,
            'parent_id'       	=> $category->parent_id,
            'top'      			=> $category->top,
			'status'      		=> $category->status,
  	    ];
  	}
	
	public function IncludeChildren(Category $category) {
		$children = $category->childrenRecursive;
		if($children == null) {
			return null;
		}
        return $this->Collection($children, new ChildrenRecursiveTransformer);
	}
}
