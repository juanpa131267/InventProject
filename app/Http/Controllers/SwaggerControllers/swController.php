<?php

namespace App\Http\Controllers\SwaggerControllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class swController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
