<?php

namespace App\Http\Controllers;

use App\Http\Resources\CounterResource;
use App\Models\Counter;
use Illuminate\Http\Request;
use Inertia\Response;

class CounterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $counters = CounterResource::collection(
            Counter::all()
        )->toArray(request());

        return inertia('Counters/Index', [
            'counters' => $counters,
        ]);
    }
}
