<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BranchResource;
use App\Http\Resources\MediaResource;
use App\Http\Resources\TypeResource;
use App\Models\Branch;
use App\Models\Type;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VehicleTypeController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        try{

            $type = Type::where('status', 1);

            if($request->featured) {
                $type= $type->where('featured', $request->featured);
            }
            
            $type = $type->get();
            // dd($type);
            $type = TypeResource::collection($type);

            return $this->successResponse($type, 'success');
        }catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th, 500);
        }
    }
    

    
}
