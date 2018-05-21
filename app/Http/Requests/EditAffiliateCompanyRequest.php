<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditAffiliateCompanyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
	
	/**
     * Get the validation messages that apply to the request.
	 *
     * This will overwrite the other messages
	 *
     * @return array
     */
	public function messages()
	{
		return [
			'affiliate_id.required' => 'Seems you are not logged in try reloading the page or contact administrator',
		];
	}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'website'					=> 'active_url',
			'street1'         			=> 'required',
			'street2'         			=> 'required',
			'postcode'					=> 'required|min:6|numeric|digits:6',
			'city'						=> 'required',
			'country'					=> 'required',
			'updated_by'				=> 'required',
			'affiliate_id'				=> 'required',
        ];
    }
    
    public function response(array $errors)
    {
        return $this->setStatusCode(400)->respondWithError('INVALID_REQUEST', array_shift($errors)[0]);
    }
}
