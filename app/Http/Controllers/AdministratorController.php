<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;

class AdministratorController extends Controller
{
    //
    function __construct()
    {
        $this->middleware(AdminMiddleware::class);
    }
}
