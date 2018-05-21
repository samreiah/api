<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class MakeOrderRequest extends Request
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
			 'payment_method_id'         => 'required|numeric',
			 'customer_id'		         => 'required|numeric',
			 'updated_by'                => 'required|numeric',
             'shipping_address_id'       => 'required|numeric',
             'billing_address_id'        => 'required|numeric',
             'shipping_method_id'        => 'required|numeric',
        ];
    }
	
	 public function response(array $errors)
    {
        return $this->setStatusCode(400)->respondWithError('INVALID_REQUEST', array_shift($errors)[0]);
    }
}
