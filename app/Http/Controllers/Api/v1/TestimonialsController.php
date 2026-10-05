<?php

namespace App\Http\Controllers\Api\v1;
use App\Http\Controllers\Controller;

use App\Http\Resources\TestimonialResource;

use App\Models\Testimonial;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestimonialsController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $testimonial = Testimonial::where('status', 1)->get();
            $testimonial = TestimonialResource::collection($testimonial);

            return $this->successResponse($testimonial, 'success');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th, 500);
        }
    }
}
