<?php

namespace App\Http\Controllers\Api\v1;
use App\Http\Controllers\Controller;
use App\Http\Resources\ManufactureResource;
use App\Models\Manufacture;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ManufactureController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $manufacture = Manufacture::where('status', 1);
            if($request->featured) {
                $manufacture= $manufacture->where('featured', $request->featured);
            }
            $manufacture = $manufacture->with('media')->get();
            $manufacture = ManufactureResource::collection($manufacture);

            return $this->successResponse($manufacture, 'success');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th, 500);
        }
    }
}
