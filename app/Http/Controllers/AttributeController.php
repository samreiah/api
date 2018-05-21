<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;


use Auth;
use App\Attribute;
use App\AttributeGroup;
use App\Transformers\AttributeGroupTransformer;

class AttributeController extends ApiController
{
    public function getAttributeGroups() {
		$attribute_groups = AttributeGroup::orderBy('sort_order', 'desc');
		
		return $this->respondWithSuccess(
            'ATTRIBUTE_GROUPS_FOUND', NULL,
            $this->createCollectionData($attribute_groups, new AttributeGroupTransformer)
        );
	}
	
	public function getAttributeGroup($group_id) {
		$attribute_group = AttributeGroup::findorFail($group_id);
		
		return $this->respondWithSuccess(
            'ATTRIBUTE_GROUP_FOUND', NULL,
			$this->createData(new Item($attribute_group, new AttributeGroupTransformer))
        );
	}
	
	public function getAttributesByGroupId($group_id) {
		//
	}
}
