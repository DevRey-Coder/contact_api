<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Http\Requests\StoreFavoriteRequest;
use App\Http\Requests\UpdateFavoriteRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $favorites = $user->favorites;

        return ContactResource::collection($favorites);
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
    public function storeFavorite(Request $request, Contact $contact)
    {
        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }
        $user = Auth::user();
        $user->favorites()->attach($contact->id);

        return response()->json(['message' => 'Contact added to favorite list successfully'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Favorite $favorite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Favorite $favorite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFavoriteRequest $request, Favorite $favorite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Contact $contact)
    {
        $user = auth()->user();
        $user->favorites()->detach($contact->id);

        return response()->json(['message' => 'Contact removed from favorites successfully']);
    }
}
