<?php

namespace App\Http\Controllers;

use App\Http\Resources\SearchLogResource;
use App\Models\Contact;
use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchLogController extends Controller
{
    public function store(Request $request)
    {
        $keyword = $request->input('keyword');

        $searchResults = Contact::where('name', 'like', "%{$keyword}%")
            ->orWhere('phone_number', 'like', "%{$keyword}%")
            ->get();

        return response()->json(['results' => $searchResults], 200);
    }
}
