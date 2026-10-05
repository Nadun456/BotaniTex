<?php

namespace App\Http\Controllers;

use App\Models\BikeContact;
use App\Models\BikeInquiry;
use App\Models\BikeManufacture;
use App\Models\Inquiry;
use App\Models\Manufacture;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class InquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Inquiries/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getData()
    {
        $users = BikeInquiry::all();
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
                    $action_html .= '<a class="dropdown-item action_edit" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" href="javascript:void(0)"><i class="fas fa-edit mr-2"></i> View</a>';
                }
                // if (auth()->user()->can('backend-user.edit')) {
                //     $action_html .= '<a class="dropdown-item ' . ($row->status == 1 ? 'text-warning' : 'text-success') . ' action_status_change" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" data-status="' . $row->status . '" href="javascript:void(0)"><i class="fas fa-power-off mr-2"></i>' . ($row->status == 1 ? ' Deactivate' : ' Activate') . '</a> ';
                // }
                // $action_html .= '<div class="dropdown-divider"></div>';
                // if (auth()->user()->can('backend-user.delete')) {
                //     $action_html .= '<a class="dropdown-item text-danger action_delete" data-bs-toggle="modal" data-bs-target="#deleteConfirm" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" href="javascript:void(0)"><i class="fas fa-trash mr-2"></i> Delete</a> ';
                // }
                return '<div class="btn-group">
                                <button type="button" class="btn btn-main btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu" style="min-width: 10rem;">
                                ' . $action_html . '
                                </div>
                            </div>';
            })->addColumn('status', function ($row) {
                if ($row->status == 'done' && !$row->deleted_at) {
                    return '<span class="badge bg-success">Done</span>';
                } else if ($row->status == 'close' && !$row->deleted_at) {
                    return '<span class="badge bg-warning">Close</span>';
                } else if ($row->status == 'pending' && !$row->deleted_at) {
                    return '<span class="badge bg-primary">Pending</span>';
                } else if ($row->status == 'handling' && !$row->deleted_at) {
                    return '<span class="badge bg-success">Handling</span>';
                }else if ($row->status == 'reject' && !$row->deleted_at){
                    return '<span class="badge bg-danger">Reject</span>';
                }else if ($row->deleted_at) {
                    return '<span class="badge bg-danger">Suspended</span>';
                }
            })->addColumn('product_manufacture', function ($row){
                $manufacture = BikeManufacture::find($row->product_manufacture);
                return $manufacture->name;
            })
            ->rawColumns(['check','name', 'email', 'mobile', 'comment', 'product_name', 'product_manufacture', 'action'])
            ->make(true);
    }


    public function edit($id, Request $request)
    {
        $inquiry = BikeInquiry::find($id);
        // dd($inquiry);

        $manufacture = BikeManufacture::find($inquiry->product_manufacture);
//  dd($manufacture);
        return Inertia::render('Inquiries/CreateUpdate', ['inquiry' => $inquiry,'manufacture'=>$manufacture]);
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
    public function show(Inquiry $inquiry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
  
     

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //  dd($request->all());
        $request->validate([
            "url" => "required",
            "vehicle_id" => "required",
            "name" => "required",
            "email" => "required|email",
            "phone" => "required",
            "purchase_time" => "required",
            "payment_method" => "required",
            "address" => "required",
            "message" => "nullable",
            "type" => "required",
            "status" => "required",
       
        ]);
       
        try {
            // dd($request->all());
            DB::beginTransaction();
            $inquiry = Inquiry::find($request->id);
            // dd($inquiry);
            $inquiry->name = $request->name;
            $inquiry->email = $request->email;
            $inquiry->phone = $request->phone;
            $inquiry->address = $request->address;
            $inquiry->message = $request->message;
            $inquiry->purchase_time = $request->purchase_time;
            $inquiry->payment_method = $request->payment_method;
            $inquiry->url = $request->url;
            $inquiry->vehicle_id = $request->vehicle_id;
            $inquiry->type = $request->type;
            $inquiry->status = $request->status;

        //    dd($inquiry);
            $inquiry->save();
            DB::commit();

            return redirect()->route('inquiry.index');
        } catch (Exception $ex) {
            dd($ex);
            DB::rollBack();
            Log::error($ex);
            abort(500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // dd($request->all());
        try {
            Inquiry::destroy([$request->ids]);
            return redirect()->route('inquiry.index');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }
}
