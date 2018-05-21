<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\Product;
use App\Transformers\ManufacturerTransformer;
use App\Transformers\lengthclassTransformer;
use App\Transformers\WeightClassTransformer;
use App\Transformers\ProductDiscountTransformer;
use App\Transformers\ProductOptionTransformer;

class ListProductsTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'manufacturer', 'weightClass', 'lengthClass', 'discount', 'image', 'options'
    ];

    protected $defaultIncludes = [
        'manufacturer', 'weightClass', 'lengthClass', 'discount', 'image', 'options'
    ];

    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(Product $product)
  	{
  	    return [
            'product_id'       	=> $product->id,
            'product_name'     	=> $product->name,
            'slug'             	=> $product->slug,
            'description'      	=> $product->description,
			'tag'				=> $product->tag,
            'meta_title'        => $product->meta_title,
            'meta_description'  => $product->meta_description,
            'meta_keyword'      => $product->meta_keyword,
            'model'             => $product->model,
            'sku'          		=> $product->sku,
            'upc'           	=> $product->upc,
			'ean'             	=> $product->ean,
            'jan'         		=> $product->jan,
			'isbn'				=> $product->isbn,
            'spn'            	=> $product->spn,
            'location'         	=> $product->location,
            'quantity'          => $product->quantity,
            'rating'            => $product->rating,
            'stock_status'      => $product->stock_status,
            'manufacturer_id'   => $product->manufacturer_id,
			'affiliate_id'      => $product->affiliate_id,
            'price'         	=> $product->price,
			'date_available'	=> $product->date_available,
            'status'            => $product->status,
            'approved'         	=> $product->approved,
            'weight'            => $product->weight,
            'weight_class_id'   => $product->weight_class_id,
            'length'          	=> $product->length,
            'width'           	=> $product->width,
			'height'            => $product->height,
            'length_class_id'   => $product->length_class_id,
  	    ];
  	}

    public function includeManufacturer(Product $product)
    {
        $manufacturer = $product->manufacturer;
		if($manufacturer == null) {
			return null;
		}
		
        return $this->item($manufacturer, new ManufacturerTransformer);
    }
	
	public function includeLengthClass(Product $product)
    {
        $lengthclass = $product->lengthClass;
		if($lengthclass == null) {
			return null;
		}
		
        return $this->item($lengthclass, new lengthClassTransformer);
    }
	
	public function includeWeightClass(Product $product)
    {
        $weightclass = $product->weightClass;
		if($weightclass == null) {
			return null;
		}
		
        return $this->item($weightclass, new WeightClassTransformer);
    }
	
	public function includeDiscount(Product $product)
    {
        $discount = $product->activeDiscount;
		if($discount == null) {
			return null;
		}
		
        return $this->item($discount, new ProductDiscountTransformer);
    }
	
	public function includeImage(Product $product) {
		$image = $product->firstImage;
		if($image == null) {
			return null;
		}
		
		return $this->item($image, new ProductImageTransformer);
	}
	
	public function includeOptions(Product $product) {
		$options = $product->options;
		if($options == null) {
			return null;
		}
		
		return $this->Collection($options, new ProductOptionTransformer);
	}
}
