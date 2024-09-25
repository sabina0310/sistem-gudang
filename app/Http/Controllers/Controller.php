<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function error_handler($e)
    {
        if (1) { # if not production
            throw $e;
        }
        return $this->responseBack("Terjadi Kesalahan Server", 500);
    }
}
