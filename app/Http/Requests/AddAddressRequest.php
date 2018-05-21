<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddAddressRequest extends Request
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
			'customer_id.required' => 'Seems you are not logged in try reloading the page or contact administrator',
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
			'address_name'				=> 'required',
			'street1'         			=> 'required',
			'street2'         			=> 'required',
			'postcode'					=> 'required|min:6|numeric|digits:6',
			'country'					=> 'required',
			'mobile'     				=> 'required|min:6|numeric|digits:10',
			'telephone' 				=> 'required|min:6|numeric',
			'customer_id'				=> 'required',
        ];
    }
	
    public function response(array $errors)
    {
		session()->flash('danger_notification', 'There are some errors in the form');
        return redirect()->back()->withErrors($errors)->withInput($this->request->all());
    }
}
