<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class VehicleTypeController extends Controller
{
    public function index()
    {
        return Inertia::render('VehicleCMS/VehicleType/Index');
    }

    public function getData()
    {
        $users = Type::with('media')->get();
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
                    $image = '<img src="' . $row->media[0]->original_url . '" height="25"/>';
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

        return Inertia::render('VehicleCMS/VehicleType/CreateUpdate');
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'title' => ['required'],
            "status"=> ['required'],
            "featured"=> ['required'],
            'profile_image' => ['nullable', 'mimes:jpeg,jpg,png,webp', 'max:10000']
        ]);
        // dd($request->all());
        try {
            DB::beginTransaction();

            $type = new Type();
            $type->title = $request->title;
            $type->status = $request->status;
            $type->featured = $request->featured;
            $type->save();


            if ($request->hasFile('vehicle_type_image')) {
                $type->addMedia($request->file('vehicle_type_image'))->toMediaCollection('vehicle_type_image');
                $type->save();
            }

            DB::commit();




            return redirect()->route('vehicle.type.index');
        } catch (Exception $ex) {
            dd($ex);
            DB::rollBack();
            return abort(500);
        }
    }

    public function edit($id)
    {
        $vehicle_types = Type::with('media')->find($id);
        // dd($vehicle_types);
        return Inertia::render('VehicleCMS/VehicleType/CreateUpdate', ['vehicle_types' => $vehicle_types]);
    }
    public function updateStatus(Request $request)
    {
        // dd($request->all());
        try {
            $user = Type::find($request->id);
            if ($request->status == 0) {
                $user->status = 1;
            } else {
                $user->status = 0;
            }
            $user->save();

            return redirect()->route('vehicle.type.index');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }

    public function update(Request $request)
    {
        //  dd($request->all());
        $request->validate([
            'title' => ['required'],
            "status"=> ['required'],
            "featured"=> ['required'],
            'profile_image' => ['nullable', 'mimes:jpeg,jpg,png,webp', 'max:10000']
        ]);
        
        try {
            DB::beginTransaction();
            $type = Type::find($request->id);
            $type->title = $request->title;
            $type->status = $request->status;
            $type->featured = $request->featured;
            $type->save();
            DB::commit();



            if ($request->hasFile('vehicle_type_image')) {
                if ($type->media) {
                    Storage::disk('public')->delete($type->media);
                    $type->clearMediaCollection('vehicle_type_image');
                }
                $type->addMedia($request->file('vehicle_type_image'))->toMediaCollection('vehicle_type_image');
                $type->save();
            }

            return redirect()->route('vehicle.type.index');
        } catch (Exception $ex) {
            // dd($ex);
            DB::rollBack();
            Log::error($ex);
            abort(500);
        }
    }

    public function destroy(Request $request)
    {
        // dd($request->all());
        try {
            Type::destroy([$request->ids]);
            return redirect()->route('vehicle.type.index');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }
}
