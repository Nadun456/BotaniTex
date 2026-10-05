<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    
    public function index()
    {
        return Inertia::render('Customer/Index');
    }

    public function getData()
    {
        $users = Customer::with('media')->get();
        // dd($users);
        return DataTables::of($users)
            ->addColumn('check', function ($row) {
                return '<div class="custom-control custom-checkbox item-check">
            <input type="checkbox" class="form-check-input" id="' . $row->id . '" value="' . $row->id . '">
            <label class="form-check-label form-check-label" for="' . $row->id . '"></label>
          </div>';
            })

            ->addColumn('action',  function ($row) {



                $action_html = '';
                if (auth()->user()->can('backend-user.view backend-user.edit')) {
                    $action_html .= '<a class="dropdown-item action_edit" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" href="javascript:void(0)"><i class="fas fa-edit mr-2"></i> View / Edit</a>';
                }
                if (auth()->user()->can('backend-user.edit')) {
                    $action_html .= '<a class="dropdown-item ' . ($row->status == 1 ? 'text-warning' : 'text-success') . ' action_status_change" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" data-status="' . $row->status . '" href="javascript:void(0)"><i class="fas fa-power-off mr-2"></i>' . ($row->status == 1 ? ' Deactivate' : ' Activate') . '</a> ';
                }
                $action_html .= '<div class="dropdown-divider"></div>';
                if (auth()->user()->can('backend-user.delete')) {
                    $action_html .= '<a class="dropdown-item text-danger action_delete" data-bs-toggle="modal" data-bs-target="#deleteConfirm" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" href="javascript:void(0)"><i class="fas fa-trash mr-2"></i> Delete</a> ';
                }
                return '<div class="btn-group">
                                <button type="button" class="btn btn-main btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu" style="min-width: 10rem;">
                                ' . $action_html . '
                                </div>
                            </div>';
            })->addColumn('status', function ($row) {
                if ($row->status == 1 && !$row->deleted_at) {
                    return '<span class="badge bg-success">Active</span>';
                } else if ($row->status == 0 && !$row->deleted_at) {
                    return '<span class="badge bg-warning">Inactive</span>';
                } else if ($row->deleted_at) {
                    return '<span class="badge bg-danger">Suspended</span>';
                }
            })
            ->addColumn('image', function ($row) {
                if (count($row->media) > 0) {
                    $image = '<img src="' . $row->media[0]->original_url . '" height="50"/>';
                } else {
                    $image = "No Image";
                }

                return $image;
            })
            ->rawColumns(['check', 'action', 'status', 'image'])
            ->make(true);
    }

    public function create()
    {
        return Inertia::render('Customer/CreateUpdate');
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => ['required'],
            'status' => ['required'],
            'email' => ['required', 'email'],
            'phone' => ['required'],
            'address' => ['required'],
            'password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'same:password'],
            'profile_image' => ['nullable', 'mimes:jpeg,jpg,png,webp', 'max:10000']
        ]);
    
        try {
            DB::beginTransaction();
    
            // Create a new Customer instance and save data
            $customer = new Customer();
            $customer->name = $request->name;
            $customer->status = $request->status;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->address = $request->address;
            $customer->password = bcrypt($request->password); // Encrypt the password
            $customer->save();
    
            // Save profile image if uploaded
            if ($request->hasFile('profile_image')) {
                $customer->addMedia($request->file('profile_image'))->toMediaCollection('Customer_image');
            }
    
            DB::commit();
    
            return redirect()->route('customer.index')->with('success', 'Customer created successfully.');
        } catch (Exception $ex) {
            // dd($ex);
            DB::rollBack();
            return abort(500, 'An error occurred while creating the customer.');
        }
    }

    public function edit($id)
    {
        $customer = Customer::with('media')->find($id);
        // dd($vehicle_types);
        return Inertia::render('Customer/CreateUpdate', ['customer' => $customer]);
    }

    public function update(Request $request)
    {
        // Validate the request data
        $request->validate([
            // 'name' => ['required'],
            // 'status' => ['required'],
            // 'email' => ['required', 'email'],
            // 'phone' => ['required'],
            // 'address' => ['required'],
            'current_password' => ['nullable', 'min:8'], // Optional current password field
            'new_password' => ['nullable', 'min:8'], // Optional new password field
            'confirm_new_password' => ['nullable', 'same:new_password'], // Must match new password
            // 'profile_image' => ['nullable', 'mimes:jpeg,jpg,png,webp', 'max:10000']
        ]);
    
        try {
            DB::beginTransaction();
    
            $customer = Customer::find($request->id);
            $customer->name = $request->name;
            $customer->status = $request->status;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->address = $request->address;
    
            // Check if current password is provided
            if ($request->filled('current_password')) {
                // Verify the current password
                if (!Hash::check($request->current_password, $customer->password)) {
                    return back()->withErrors(['current_password' => 'The current password is incorrect.']);
                }
    
                // Update the password if a new one is provided
                if ($request->filled('new_password')) {
                    $customer->password = bcrypt($request->new_password);
                }
            }
    
            $customer->save();
    
          
            if ($request->hasFile('profile_image')) {
                if ($customer->media) {
                    Storage::disk('public')->delete($customer->media);
                    $customer->clearMediaCollection('Customer_image');
                }
                $customer->addMedia($request->file('profile_image'))->toMediaCollection('Customer_image');
            }
    
            DB::commit();
    
            return redirect()->route('customer.index')->with('success', 'Customer updated successfully.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);
            return abort(500, 'An error occurred while updating the customer.');
        }
    }
    

    public function destroy(Request $request)
    {
        // dd($request->all());
        try {
            Customer::destroy([$request->ids]);
            return redirect()->route('customer.index');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }
    public function updateStatus(Request $request)
    {
        // dd($request->all());
        try {
            $user = Customer::find($request->id);
            if ($request->status == 0) {
                $user->status = 1;
            } else {
                $user->status = 0;
            }
            $user->save();

            return redirect()->route('customer.index');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }

    
}
