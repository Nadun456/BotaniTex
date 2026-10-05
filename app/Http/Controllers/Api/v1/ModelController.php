<?php

namespace App\Http\Controllers\Api\v1;
use App\Http\Controllers\Controller;
use App\Http\Resources\ManufactureResource;
use App\Http\Resources\ModelResource;
use App\Models\Manufacture;
use App\Models\VehicleModel;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ModelController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $model = VehicleModel::where('status', 1)->get();
            $model = ModelResource::collection($model);

            return $this->successResponse($model, 'success');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th, 500);
        }
    }
}
