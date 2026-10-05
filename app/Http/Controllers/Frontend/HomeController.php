<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BikeCategory;
use App\Models\BikeManufacture;
use App\Models\BikeModel;
use App\Models\BikeProduct;
use App\Models\Manufacture;
use App\Models\Type;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use App\Services\ApiClient\ApiClient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->all());
        $category_manufactures = Manufacture::where(['status' => 1, 'featured' => 1])->get();
        $vehicle_types = Type::where(['status' => 1, 'featured' => 1])->get();
        $models = VehicleModel::where(['status' => 1])->where(['manufacture_id' => $request->manufacture_id])->get();
        $vehicles = Vehicle::where(['status' => 1]);
        $in_stock = Vehicle::where(['availability' => 'Available', 'status' => 1, 'featured' => 1])->with('media', 'manufacture', 'vehicleModel')->limit(4)->get();

        $currentYear = date('Y');
        $baseYear = $currentYear - 5;

        $latest_cars = Vehicle::where(['availability' => 'Available', 'status' => 1, 'featured' => 1])->whereBetween('year', [$baseYear, $currentYear])->with('media', 'manufacture', 'vehicleModel')->limit(4)->latest()->get();

        // dd($latest_cars);

        if ($request->manufacture_id) {
            $vehicles = $vehicles->where('manufacture_id', $request->manufacture_id);
        }

        if ($request->vehicle_type_id) {
            $vehicles = $vehicles->where('vehicle_type_id', $request->vehicle_type_id);
        }

        if ($request->model_id) {
            $vehicles = $vehicles->where('vehicle_model_id', $request->model_id);
        }

        $minYear = clone $vehicles;
        $minYear = $minYear->min('year');
        $maxYear = clone $vehicles;
        $maxYear =  $maxYear->max('year');

        $minMileage = clone $vehicles;
        $minMileage = $minMileage->min('mileage');
        $maxMileage = clone $vehicles;
        $maxMileage = $maxMileage->max('mileage');

        $years = range($minYear, $maxYear);
        $years = array_filter($years);

        $featuredVehicles = Vehicle::where(['status' => 1, 'featured' => 1])->with('media', 'manufacture', 'vehicleModel')->limit(5)->get();
        // dd($featuredVehicles);

    

        // live auction filteration



        $bikeManufactures = BikeManufacture::with('media')->where('status', 1)->limit(8)->get(); 
        $bikeModel = BikeModel::with('media')->where('status', 1)->limit(8)->get(); 
        $bikeCategory = BikeCategory::with('media')->where('status', 1)->limit(8)->get(); 
        $best_selling_product = BikeProduct::with('media')->where(['status' =>1, 'featured' => 'best_selling'] )->limit(4)->get(); 
        // dd($best_selling_product->all());


        return Inertia::render('Home/index', [
          
             'vehicle_types' => $vehicle_types,
              'category_manufactures' => $category_manufactures,
               'models' => $models, 'years' => $years,
                'minMileage' => $minMileage,
                 'maxMileage' => $maxMileage,
                  'featuredVehicles' => $featuredVehicles,
                   'in_stock' => $in_stock,
                    'latest_cars' => $latest_cars,
                    'bikeManufactures' => $bikeManufactures,
                    'bikeModel' => $bikeModel,
                    'bikeCategory' => $bikeCategory,
                    'best_selling_product' => $best_selling_product
                ]);
    }
}
