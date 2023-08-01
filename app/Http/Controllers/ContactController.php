<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactDetailResource;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Access\Gate as AccessGate;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate as FacadesGate;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::when(request()->has("keyword"), function ($query) {
            $query->where(function (Builder $builder) {
                $keyword = request()->keyword;

                $builder->where("name", "like", "%" . $keyword . "%");
                $builder->orWhere("country_code", "like", "%" . $keyword . "%");
            });
        })->latest('id')->where('is_deleted', false)->paginate(5)->withQueryString();
        return ContactResource::collection($contacts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "country_code" => "required|min:1|max:265",
            "phone_number" => "required"
        ]);

        $contact = Contact::create([
            "name" => $request->name,
            "country_code" => $request->country_code,
            "phone_number" => $request->phone_number,
            'user_id' => Auth::id()
        ]);

        return new ContactDetailResource($contact);
    }

    public function restore(string $id)
    {
        $contact = Contact::withTrashed()->find($id);
        if (is_null($contact)) {
            return response()->json([
                'message' => 'data not found'
            ], 404);
        }

        if ($contact->trashed()) {
            if (FacadesGate::allows('restore', $contact)) {
                $contact->restore();
                return response()->json([
                    'message' => 'data' . $id . 'has been restored'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'You are not allowed to restore this contact.'
                ], 403);
            } 
        }
    }

    public function restoreMultiple(Request $request,$ids){
        $ids = explode(',',$ids);
        $contacts = Contact::onlyTrashed()->whereIn('id',$ids)->get();
        foreach($contacts as $contact){
            $contact->restore();
        }

        return response()->json(['message' => 'multiple contact restored successfully'],200);
    }



    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {

        $contact = Contact::find($id);
        if (is_null($contact)) {
            return response()->json([
                'message' => 'data not found'
            ], 404);
        }

        // if ($contact->trashed()) {
        //     if (FacadesGate::allows('restore', $contact)) {
        //         $contact->restore();
        //     } else {
        //         return response()->json([
        //             'message' => 'You are not allowed to restore this contact.'
        //         ], 403);
        //     }
        // }

        // $this->authorize('view',$contact);

        if (FacadesGate::denies('view', $contact)) {
            return response()->json([
                'message' => 'U are not allowed'
            ], 403);
        }

        return new ContactDetailResource($contact);
    }

    public function showRestore()
    {
        $contact = Contact::onlyTrashed()->get();
        // $contact = Contact::withTrashed()->find($id);
        if ($contact->isEmpty()) {
            return response()->json([
                'message' => 'data not found'
            ], 404);
        }

        return ContactResource::collection($contact);
    }

    public function restoreAll(){
        Contact::withTrashed()->restore();

        return response()->json([
            'message' => 'Your trash are all restored'
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "name" => "nullable|min:3|max:20",
            "country_code" => "nullable|integer|min:1|max:265",
            "phone_number" => "nullable|min:7|max:15"
        ]);

        $contact = Contact::find($id);
        if (is_null($contact)) {
            return response()->json([
                'message' => 'contact not found'
            ], 404);
        }

        // $contact->update([
        //     "name" => $request->name,
        //     "country_code" => $request->country_code,
        //     "phone_number" => $request->phone_number
        // ]);

        // Security ကို မလိုဘူးထင်တဲ့အခါ ဒါကိုသုံး
        // $contact->update($request->all());

        // ဒါကိုကျ လုံလုံခြုံခြုံရှိချင်ရင် သုံး
        if ($request->has('name')) {
            $contact->name = $request->name;
        }

        if ($request->has('country_code')) {
            $contact->country_code = $request->country_code;
        }

        if ($request->has('phone_number')) {
            $contact->phone_number = $request->phone_number;
        }

        $contact->update();

        return new ContactDetailResource($contact);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact = Contact::find($id);
        $contact->delete();

        return response()->json([], 204);
    }

    /**
     *Remove Forever the specified resource
     * */

    public function forceDelete($id)
    {
        $contact = Contact::withTrashed()->findOrFail($id);
        $contact->forceDelete();

        return response()->json([], 204);
    }

    public function forceDeleteAll(){
        Contact::onlyTrashed()->forceDelete();

        return response()->json([
            'message' => 'All contacts force deleted successfully'
        ],203);
    }
}
