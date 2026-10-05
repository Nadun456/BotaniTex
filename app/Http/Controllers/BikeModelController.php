<?php

namespace App\Http\Controllers;

use App\Models\BikeManufacture;
use App\Models\BikeModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class BikeModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('BikeModel/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    { 
        $manufacture = BikeManufacture::where(['status' => 1])->get();
        // dd($manufacture->all());
        return Inertia::render('BikeModel/CreateUpdate',['manufacture'=> $manufacture]);
    }

    public function getData()
    {
        $users = BikeModel::with('media')->get();
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
            ->rawColumns(['check', 'action', 'status', 'image', 'name'])
            ->make(true);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        // dd($request->all());
        $request->validate([
            'name' => ['required'],
            "status"=> ['required'],
            "manufacture"=> ['required'],
            'Model_image' => ['nullable', 'mimes:jpeg,jpg,png,webp', 'max:10000']
        ]);
        // dd($request->all());
        try {
            DB::beginTransaction();

            $model = new BikeModel();
            $model->name = $request->name;
            $model->manufacture = $request->manufacture;
            $model->status = $request->status;
       
            $model->save();


            if ($request->hasFile('Model_image')) {
                $model->addMedia($request->file('Model_image'))->toMediaCollection('Model_image');
                $model->save();
            }

            DB::commit();




            return redirect()->route('model.index');
        } catch (Exception $ex) {
            dd($ex);
            DB::rollBack();
            return abort(500);
        }
    }

    public function updateStatus(Request $request)
    {
        // dd($request->all());
        try {
            $user = BikeModel::find($request->id);
            if ($request->status == 0) {
                $user->status = 1;
            } else {
                $user->status = 0;
            }
            $user->save();

            return redirect()->route('model.index');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BikeModel $bikeModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $model = BikeModel::with('media')->find($id);
        $manufacture = BikeManufacture::all();
        // dd($model->all());
        return Inertia::render('BikeModel/CreateUpdate', ['model' => $model, 'manufacture'=>$manufacture]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //  dd($request->all());
        try {
            DB::beginTransaction();
            $model = BikeModel::find($request->id);
            $model->name = $request->name;
            $model->manufacture = $request->manufacture;
            $model->status = $request->status;
            $model->save();
            DB::commit();



            if ($request->hasFile('Model_image')) {
                if ($model->media) {
                    Storage::disk('public')->delete($model->media);
                    $model->clearMediaCollection('Model_image');
                }
                $model->addMedia($request->file('Model_image'))->toMediaCollection('Model_image');
                $model->save();
            }

            return redirect()->route('model.index');
        } catch (Exception $ex) {
            // dd($ex);
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
            BikeModel::destroy([$request->ids]);
            return redirect()->route('model.index');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }
}
