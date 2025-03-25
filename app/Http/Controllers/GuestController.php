<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
   
    public function index()
    {
        $guests = Guest::all();
        return view('guests.index', compact('guests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'seminar_id' => 'required',
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);

        $guest = Guest::create($request->all());

        return response()->json([
            'success' => 'Guest created successfully.',
            'guest_id' => $guest->id, // Return guest ID
        ], 200);
    }

    public function update(Request $request, Guest $guest)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);

        $guest->update($request->all());

        return response()->json(['success' => 'Guest updated successfully.', 200]);
    }



}
