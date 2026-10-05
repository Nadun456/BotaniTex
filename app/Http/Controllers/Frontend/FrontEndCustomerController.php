<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FrontEndCustomerController extends Controller
{
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'password' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $customer = new Customer();
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->password = $request->password;
            $customer->save();

            DB::commit();

            return redirect()->route('user.login');
        } catch (Exception $ex) {
            // dd($ex);
            DB::rollBack();
            return abort(500);
        }
    }   
}
