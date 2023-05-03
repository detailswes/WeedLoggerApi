<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
    * @OA\Info(
    *      version="1.0.0",
    *      title="Weed Logger APIs",
    *      description="Weed Logger APIs",
    * )
*/
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
