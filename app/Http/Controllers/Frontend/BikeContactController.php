<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BikeContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BikeContactController extends Controller
{
    public function contactMessage(Request $request)
    {
        // dd($request->all());


        $request->validate([

            "name" => "required",
            "email" => "required|email",
            "mobile" => "required",
            "subject" => "required",
            "comment" => "required",

        ]);

        //   dd($request->all());
        DB::beginTransaction();
        try {
            $contact = new BikeContact();
            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->mobile = $request->mobile;
            $contact->subject = $request->subject;
            $contact->message = $request->comment;

            $contact->save();

            DB::commit();

            return redirect()->route('home');
        } catch (\Throwable $th) {
            dd($th);
            Log::error($th);
            DB::rollBack();

            return redirect()->back();
        }
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
    public function show(BikeContact $bikeContact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BikeContact $bikeContact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BikeContact $bikeContact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BikeContact $bikeContact)
    {
        //
    }
}
