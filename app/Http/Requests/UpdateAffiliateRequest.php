<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateAffiliateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
			 'id'				=> 'required|numeric',
             'firstname'    	=> 'required|string',
			 'lastname'    		=> 'string',
			 'mobile'			=> 'numeric',
			 'telephone'		=> 'numeric',
             'company_name' 	=> 'required|unique:affiliates,company_name'.$request->id,
			 'updated_by'		=> 'required|numeric',
        ];
    }
	
	 public function response(array $errors)
    {
        return $this->setStatusCode(400)->respondWithError('INVALID_REQUEST', array_shift($errors)[0]);
    }
}
