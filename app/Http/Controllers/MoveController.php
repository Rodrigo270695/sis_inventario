<?php

namespace App\Http\Controllers;

use App\Models\Move;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MoveController extends Controller
{

    public function index(): Response
    {
        return Inertia::render('Operation/Move/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Move $move)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Move $move)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Move $move)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Move $move)
    {
        //
    }
}
