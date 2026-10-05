<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;
use Inertia\Inertia;

use function PHPSTORM_META\type;

class AvailableStockController extends Controller
{
    public function index($id)
    {
        $type = Type::with('media')->find($id);
        dd($type);
        return Inertia::render('Available_stock/Types', ['type'=>$type] );
    }
}