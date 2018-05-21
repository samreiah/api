<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class WriteReviewRequest extends Request
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

    public function messages()
    {
        return [
        'product_id.required'   => 'Seems you are not logged in',
        'title.string'      => 'Title should be in string',
        
        'valuerating.required'  => 'You should rate for value',
        'valuerating.numeric'   => 'value for value rating should be in number',
        'valuerating.min'   => 'minimum value should be 1',
        'valuerating.max'   => 'minimum value should be 5',

        'qualityrating.required'=> 'You should rate for Quality',
        'qualityrating.numeric' => 'value for quality rating should be in number',
        'qualityrating.min'     => 'minimum value should be 1',
        'qualityrating.max'     => 'minimum value should be 5',
        
        'pricerating.required'  => 'You should rate for Price',
        'pricerating.numeric'   => 'value for price rating should be in number',
        'pricerating.min'   => 'minimum value should be 1',
        'pricerating.max'   => 'minimum value should be 5',
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
            'product_id'            => 'required|numeric',
            'title'                 => 'required|string',
            'description'           => 'required|string',
            'valuerating'           => 'required|numeric|min:1|max:5',
            'qualityrating'         => 'required|numeric|min:1|max:5',
            'pricerating'           => 'required|numeric|min:1|max:5',

        ];
    }
	
	 public function response(array $errors)
    {
        return $this->setStatusCode(400)->respondWithError('INVALID_REQUEST', array_shift($errors)[0]);
    }
}
