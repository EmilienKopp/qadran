<?php

namespace App\Http\Controllers;

use App\Models\ClockEntry;
use App\Http\Requests\StoreClockEntryRequest;
use App\Http\Requests\UpdateClockEntryRequest;

class ClockEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreClockEntryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ClockEntry $clockEntry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClockEntry $clockEntry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClockEntryRequest $request, ClockEntry $clockEntry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClockEntry $clockEntry)
    {
        //
    }
}
