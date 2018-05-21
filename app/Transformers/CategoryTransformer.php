<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\Category;

class CategoryTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'parent', 'children'
    ];

    protected $defaultIncludes = [
        'parent', 'children'
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
	
	public function IncludeParent(Category $category) {
		$parent = $category->parent;
		if($parent == null) {
			return null;
		}
        return $this->Item($parent, new ParentCategoryTransformer);
	}
	
	public function IncludeChildren(Category $category) {
		$children = $category->children;
		if($children == null) {
			return null;
		}
		
		return $this->Collection($children, new ChildrenCategoryTransformer);
	}
}
