<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;
use App\Http\Requests\Category\CreateCategoryRequest;

use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\Category;
use App\ProductCategory;
use App\Transformers\CategoryTransformer;
use App\Transformers\ChildrenRecursiveCategoryTransformer;
use App\Transformers\ListProductsTransformer;
use App\Transformers\FilterGroupsTransformer;
use App\Transformers\ProductTransformer;

class CategoryController extends ApiController
{
    public function createCategory(CreateCategoryRequest $request) {
		
		if(isset($request->parent_id) && ($request->parent_id != 0)) {
			if($this->checkCategory($request->parent_id)) {
				return $this->respondWithError('INVALID_PARENT','Invalid Parent Category');
			}
		}
		
		$category 					= new Category();
		$category->name 			= $request->name;
		$category->description 		= $request->description;
		$category->image 			= $request->image;
		$category->meta_title 		= $request->meta_title;
		$category->meta_keyword 	= $request->meta_keyword;
		$category->meta_description = $request->meta_description;
		$category->parent_id 		= isset($request->parent_id) ? $request->parent_id : 0;
		$category->top 				= isset($request->top) ? $request->top : 0;
		$category->status 			= isset($request->status) ? $request->status : 0;
		$category->save();
		
		return $this->respondWithSuccess('CATEGORY_CREATED',"Category Created Successfully.", []);

	}
	
	private function checkCategory($parent_id) {
		
		$category = Category::find($parent_id);
		if($category == null) {
			return false;
		} else {
			return true;
		}
	}
	
	
	public function getCategories() {
		$categories 	= $this->createCollectionData(Category::with('parent', 'children')->get(), new CategoryTransformer);
		return $this->respondWithSuccess('CATEGORIES_FOUND', 'List of categories', $categories);
	}


	public function getActiveCategories() {

		$categories 	= $this->createCollectionData(Category::with('parent', 'children')->where('status', 1)->get(), new CategoryTransformer);
		return $this->respondWithSuccess('CATEGORIES_FOUND', 'List of categories', $categories->toArray());
	}

	public function getTopCategories() {
		
		$categories 	= $this->createCollectionData(Category::with('childrenRecursive')->where('top', 1)->get(), new ChildrenRecursiveCategoryTransformer);
		return $this->respondWithSuccess('CATEGORIES_FOUND', 'List of categories', $categories);
	}


	//Functions for search
	public function getProductsByCategorySlug(Request $request, $category_slug) {
		
		$category 		= Category::where('slug', $category_slug)->firstOrFail();
		$products 		= $this->listProductsofCategory($category->id);
		$filtergroups 	= $this->listFiltersofCategory($category->id);
		
		if($products == null) {
			return $this->respondWithError('NO_PRODUCTS_FOUND', 'Sorry, No product found in this category, Pleach check in other categores !', []);
		} else {
			$response = [];
			$response['products'] 	= $products;
			$response['filters'] 	= $filtergroups;
			$response['category'] 	= $category->name;
			return $this->respondWithSuccess('PRODUCTS_FOUND', 'List of products', $response);
		}
	}
	
	private function listProductsofCategory($categoryId) {
		
		$category = Category::findorFail($categoryId);
		$products = $category->products()->get();
		if(empty($products->toArray())) {
			return null;
		} else {
			return $this->createCollectionData($products, new ListProductsTransformer);
		}
	}
	
	private function listFiltersofCategory($categoryId) {
		
		$category 		= Category::findorFail($categoryId);
		$filtergrops 	= $category->filterGroups()->get();
		if(empty($filtergrops->toArray())) {
			return null;
		} else {
			return $this->createCollectionData($filtergrops, new FilterGroupsTransformer);
		}
	}


	public function getTopProducts() {
		$categories 	= Category::where('top', 1)->get();
		$products 		= [];
		$i = 0;
		foreach($categories as $category) {
			$productslist 				= $category->products()->take(5)->get();
			$products[$i]['product'] 	= $this->createCollectionData($productslist, new ProductTransformer);
			$products[$i]['category'] 	= $this->createData(new Item(Category::find($category->id), new CategoryTransformer));
			$i++;
		}

		return $this->respondWithSuccess('PRODUCTS_FOUND', 'List of products', $products);
	}
}
