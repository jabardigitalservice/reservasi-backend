<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
       /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // try to connect DB health check
        $checkDbConnection = DB::connection()->getPdo();

        return [
            'app'     => config('app.name'),
            'server'  => gethostname(),
        ];
    }
}
