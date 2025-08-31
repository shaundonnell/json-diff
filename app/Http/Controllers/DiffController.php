<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Constraint\IsJson;

class DiffController extends Controller
{
    public function index()
    {
        $payload1 = Cache::get('payload1');
        $payload2 = Cache::get('payload2');


    }

    public function store(Request $request)
    {
        if (!$request->isJson())
        {
            return response('Input must be json', 400);
        }

        if (Cache::has('payload1')) {
            Cache::put('payload2', $request->json());
        } else {
            Cache::put('payload1', $request->json());
        }

    }
}
