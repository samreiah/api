<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateCartItemRequest extends Request
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			 'id'				=> 'required|numeric',
			 'quantity'			=> 'required|numeric',
             'product_id'    	=> 'required|numeric',
			 'customer_id'		=> 'numeric',
			 'updated_by'		=> 'required|numeric',
        ];
    }
	
	 public function response(array $errors)
    {
        return $this->setStatusCode(400)->respondWithError('INVALID_REQUEST', array_shift($errors)[0]);
    }
}
