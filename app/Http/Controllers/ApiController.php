<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Traits\Serializer;


class ApiController extends Controller
{
    use ApiResponse;
    use Serializer;
	
	protected $limit 	= 10;
}