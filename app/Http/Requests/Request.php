<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiResponse;

abstract class Request extends FormRequest
{
    use ApiResponse;
}
