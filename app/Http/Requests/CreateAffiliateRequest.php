<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateAffiliateRequest extends Request
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
             'name'         => 'required',
             'company_name' => 'required|unique:affiliates,company_name',
             'email'        => 'required|unique:users,email|email|unique:affiliates,email',
             'password'     => 'required'
        ];
    }
    
    public function response(array $errors)
    {
        return $this->setStatusCode(400)->respondWithError('INVALID_REQUEST', array_shift($errors)[0]);
    }
}
