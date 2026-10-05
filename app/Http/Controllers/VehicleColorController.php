<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class VehicleColorController extends Controller
{
    public function index(){

        return Inertia::render('VehicleCMS/VehicleColor/Index');
    }

    public function store(Request $request){

        //  dd($request->all());

        $request->validate([
            'name' => ['required', 'unique:vehicle_color,name'],
            'status' => ['required'],
        ]);
        try{


        DB::beginTransaction();
        // dd($request->all());
        $vehicle_color = new Color();
        $vehicle_color->name = $request->name;
        $vehicle_color->status = $request->status;
        $vehicle_color->save();
        DB::commit();
        // dd($request->all());

        return redirect()->route('vehicle.color');

    } catch (Exception $ex) {
        // dd($ex);
        DB::rollBack();
        return abort(500);
    }

    }


    public function getData()
    {
        $users = Color::all();
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
                } else if($row->deleted_at) {
                    return '<span class="badge bg-danger">Suspended</span>';
                }
            })
            ->rawColumns(['check', 'name', 'status', 'action' ])
            ->make(true);
    }


    public function create()
    {
        return Inertia::render('VehicleCMS/VehicleColor/CreateUpdate');
    }
    public function updateStatus(Request $request)
    {
        // dd($request->all());
        try {
            $user = Color::find($request->id);
            if ($request->status == 0) {
                $user->status = 1;
            } else {
                $user->status = 0;
            }
            $user->save();

                return redirect()->route('vehicle.color');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }
    public function edit($id)
    {
        $vehicle_color = Color::find($id);
        // dd($vehicle_color);
        return Inertia::render('VehicleCMS/VehicleColor/CreateUpdate', ['vehicle_color'=> $vehicle_color]);
    }


    public function update(Request $request)
    {
        // dd($request);

        $request->validate([
           'name' => ['required'],
            'status' => ['required'],
        ]);
        // dd($request->manufacturer_name);
        try {
            DB::beginTransaction();
            $vehicle_color = Color::find($request->id);
       
            $vehicle_color->name = $request->name;
            $vehicle_color->status = $request->status;
            $vehicle_color->save();
            DB::commit();
            
            return redirect()->route('vehicle.color');
        } catch (Exception $ex) {
            // dd($ex);
            DB::rollBack();
            Log::error($ex);
            abort(500);
        }
    }
    public function delete(Request $request)
    {
        // dd($request->all());
        try {
            Color::destroy([$request->id]);
            return redirect()->route('vehicle.color');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }
}
