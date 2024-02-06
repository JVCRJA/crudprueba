<?php

namespace App\Http\Controllers;

use App\Models\Pasante;
use Illuminate\Http\Request;

class PasanteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('pasantes/index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pasantes/create');
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
    public function show(Pasante $pasante)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pasante $pasante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pasante $pasante)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pasante $pasante)
    {
        //
    }
}
