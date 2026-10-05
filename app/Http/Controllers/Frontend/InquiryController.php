<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InquiryController extends Controller
{
    public function submit(Request $request) {
        $request->validate([
            "vehicle_name" => "required",
            "vehicle_url" => "required",
            "vehicle_id" => "required",
            "stock_type" => "required",
            "name" => "required",
            "email" => "required|email",
            "mobile" => "required",
            "purchase_time" => "required",
            "payment_method" => "required",
            "address" => "required",
            "message" => "nullable",
            "capchaResponse"=>"required"
        ]);

        DB::beginTransaction();
        try {
            $inquiry = new Inquiry();
            $inquiry->name = $request->name;
            $inquiry->email = $request->email;
            $inquiry->phone = $request->mobile;
            $inquiry->address = $request->address;
            $inquiry->message = $request->message;
            $inquiry->purchase_time = $request->purchase_time;
            $inquiry->payment_method = $request->payment_method;
            $inquiry->url = $request->vehicle_url;
            $inquiry->vehicle_id = $request->vehicle_id;
            $inquiry->type = $request->stock_type;
            $inquiry->status = "pending";
            $inquiry->save();

            DB::commit();

            return redirect()->back();
        } catch (\Throwable $th) {
           Log::error($th);
           DB::rollBack();

           return redirect()->back();
        }

    }
}
