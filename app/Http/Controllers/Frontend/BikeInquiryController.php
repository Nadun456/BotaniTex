<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\InquiryMail;
use App\Models\BikeInquiry;
use App\Models\BikeProduct;
use App\Models\Inquiry;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BikeInquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function inquirySend(Request $request)
    {

        // dd($request->all());

        $request->validate([
            'productId' => 'required|exists:bike_products,id',
            'name' => ['required'],
            "email" => ['required'],
            "mobile" => ['required'],
            'quantity' => ['required'],
            'comment' => ['nullable'],
        ]);
        // dd($request->all());

        $product = BikeProduct::find($request->productId);

        // dd($product->all());

        try {
            DB::beginTransaction();

            $inquiry = new BikeInquiry();
            $inquiry->name = $request->name;
            $inquiry->email = $request->email;
            $inquiry->mobile = $request->mobile;
            $inquiry->quantity = $request->quantity;
            $inquiry->comment = $request->comment;

            $inquiry->product_name = $product->name;
            $inquiry->product_slug = $product->slug;
            $inquiry->product_sku = $product->sku;
            $inquiry->product_description = $product->description;
            $inquiry->product_manufacture = $product->manufacture;




            $inquiry->save();

            Mail::to($request->email)->send(new InquiryMail($inquiry));

            DB::commit();

            return redirect()->route('product.details.view', $product->id);
        } catch (Exception $ex) {
            dd($ex);
            DB::rollBack();
            return abort(500);
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
    public function show(BikeInquiry $bikeInquiry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BikeInquiry $bikeInquiry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BikeInquiry $bikeInquiry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BikeInquiry $bikeInquiry)
    {
        //
    }
}
