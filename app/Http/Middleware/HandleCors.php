<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\HandleCors as Middleware;

class HandleCors extends Middleware
{
    /**
     * The paths that should be excluded from CORS.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
} 