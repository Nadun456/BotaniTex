<?php

namespace App\Http\Controllers\Api\v1;
use App\Http\Controllers\Controller;
use App\Http\Resources\ManufactureResource;
use App\Http\Resources\NewsletterResource;
use App\Models\Manufacture;
use App\Models\Newsletter;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsLetterController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $newsletter = Newsletter::where('status', 1)->get();
            $newsletter = NewsletterResource::collection($newsletter);

            return $this->successResponse($newsletter, 'success');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th, 500);
        }
    }
}
