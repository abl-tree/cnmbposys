<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Common\Traits\Response;

/**
 * Class BaseController
 * @package App\Http\Controllers
 *
 * @function all() Retrieves all related data of a specific definition
 */
class BaseController extends Controller
{
    use Response;

    public function json(){
        return response()->json( $this->getResponse ( ), $this->getCode () );
    }
}
