<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;


use Auth;
use App\Country;
use App\Transformers\AffiliateTransformer;
use App\Transformers\CountryTransformer;


class CountryController extends ApiController
{
    public function getCountries() {
		$countries 	= Country::orderBy('name', 'asc')->get();
		
		return $this->respondWithSuccess(
            'COUNTRIES_FOUND', NULL,
            $this->createCollectionData($countries, new CountryTransformer)
        );
	}
	
	public function getCountry($countryId) {
		$country 		= Country::findOrFail($countryId);

		return $this->respondWithSuccess(
            'COUNTRY_FOUND', NULL,
			$this->createData(new Item($country, new CountryTransformer))
        );
	}
	
}
