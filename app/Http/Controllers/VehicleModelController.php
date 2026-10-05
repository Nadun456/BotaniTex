<?php

namespace App\Http\Controllers;

use App\Models\Manufacture;
use App\Models\User;
use App\Models\VehicleModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class VehicleModelController extends Controller
{
    public function index(){
       
    
        return Inertia::render('VehicleCMS/VehicleModel/Index');
    }

    public function store(Request $request){

        //  dd($request->all());

        $request->validate([
            'manufacture_id' => ['required'],
            'title' => ['required'],
        ]);
        try{


        DB::beginTransaction();

        $manufacture = new VehicleModel();
        $manufacture->manufacture_id = $request->manufacture_id;
        $manufacture->title = $request->title;
        $manufacture->save();
        DB::commit();
        // dd($request->all());

        return redirect()->route('vehicle.model');

    } catch (Exception $ex) {
        dd($ex);
        DB::rollBack();
        return abort(500);
    }

    }


    public function getData()
    {
        $users = VehicleModel::all();
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
            })->addColumn('manufacture_name', function ($row) {
                return $row->manufacture ? $row->manufacture->title : '';
            })
            ->rawColumns(['check', 'title', 'manufacture_name', 'status', 'action' ])
            ->make(true);
    }


    public function create()
    {
        $vehicle_manufacture = Manufacture::all();
        // dd( $vehicle_manufacture);
        return Inertia::render('VehicleCMS/VehicleModel/CreateUpdate',['vehicle_manufacture'=> $vehicle_manufacture]);
    }
    public function updateStatus(Request $request)
    {
        // dd($request->all());
        try {
            $user = VehicleModel::find($request->id);
            if ($request->status == 0) {
                $user->status = 1;
            } else {
                $user->status = 0;
            }
            $user->save();

                return redirect()->route('vehicle.model');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }
    public function edit($id)
    {
        $vehicle_models = VehicleModel::find($id);
        // dd($vehicle_models);
        $vehicle_manufacture = Manufacture::all();
        // dd($manufacture);
        return Inertia::render('VehicleCMS/VehicleModel/CreateUpdate', ['vehicle_models'=>$vehicle_models, 'vehicle_manufacture'=>$vehicle_manufacture]);
    }


    public function update(Request $request)
    {
        $request->validate([
           'manufacture_id' => ['required'],
            'title' => ['required'],
        ]);
        // dd($request->manufacturer_name);
        try {
            DB::beginTransaction();
            $vehicle_model = VehicleModel::find($request->id);
            // dd($vehicle);
            $vehicle_model->manufacture_id = $request->manufacture_id;
            $vehicle_model->title = $request->title;
            $vehicle_model->save();
            DB::commit();
            
            return redirect()->route('vehicle.model');
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
            VehicleModel::destroy([$request->id]);
            return redirect()->route('vehicle.model');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }
}

