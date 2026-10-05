<?php

namespace App\Http\Controllers\Frontend;


use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Manufacture;
use App\Models\Type;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use App\Services\ApiClient\ApiClient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function about()
    {
        // dd('test');
        return Inertia::render('About/index');
        // return Inertia::render('About/index');
    }

    public function auction(Request $request)
    {
        $id = $request->id;
        $requestData = $request->all();

        if(!$id) {
            return redirect()->back();
        }

        $query = "SELECT * FROM main WHERE id = '" . $id . "' order by marka_name ASC";
        $details = ApiClient::callAuctionApi($query, true);

        $details = count($details) > 0 ? $details[0] : null;

        $images = $details ? explode('#', $details['IMAGES']) : [];

        // stats filter queries
        $statsYearQuery = "SELECT count(year), year FROM stats ";
        $statsChassiesQuery = "SELECT count(kuzov), kuzov FROM stats ";
        $statsConditionQuery = "SELECT count(rate), rate FROM stats ";

        if($request->manufacture) {
            $statsYearQuery .= "WHERE marka_name = '" . $request->manufacture . "' ";
            $statsChassiesQuery .= "WHERE marka_name = '" . $request->manufacture . "' ";
            $statsConditionQuery .= "WHERE marka_name = '" . $request->manufacture . "' ";
        }

        if ($request->model) {
            $statsYearQuery .= "AND model_name = '" . $request->model . "' ";
            $statsChassiesQuery .= "AND model_name = '" . $request->model . "' ";
            $statsConditionQuery .= "AND model_name = '" . $request->model . "' ";
        }

        if ($request->chassis_no) {
            $statsConditionQuery .= "AND kuzov = '" . $request->chassis_no . "' ";
        }

        if ($request->year) {
            $statsChassiesQuery .= "AND year = '" . $request->year . "' ";
            $statsConditionQuery .= "AND year = '" . $request->year . "' ";
            $statsConditionQuery .= "AND year = '" . $request->year . "' ";
        }

        $statsYearQuery .= "GROUP BY year ORDER BY year DESC";
        $statsChassiesQuery.='GROUP BY kuzov ORDER BY kuzov ASC';
        $statsConditionQuery.='GROUP BY rate ORDER BY rate ASC';

        $statsYears = ApiClient::callAuctionApi($statsYearQuery);
        $statsChassis = ApiClient::callAuctionApi($statsChassiesQuery, true);
        $statsConditions = ApiClient::callAuctionApi($statsConditionQuery, true);

        $vehicleStatsList= $this->getVehicleStatsList($request);

        return Inertia::render('Auction_view/index', ['details' => $details, 'vImages' => $images, 'statsYears'=>$statsYears, 'statsChassis' =>$statsChassis, 'statsConditions'=>$statsConditions, 'requestQuery'=>$requestData, 'vehicleStatsList'=>$vehicleStatsList]);
    }
    public function Live_auction(Request $request)
    {
        $requestData = $request->all();
        
        $auctionDatesQuery = "SELECT auction_date FROM main GROUP BY DATE_FORMAT(auction_date,'%Y-%m-%d')";
        $auctionDates = ApiClient::callAuctionApi($auctionDatesQuery);

        $manufcturesQuery = "SELECT DISTINCT marka_name FROM main ORDER BY marka_name ASC";;
        $manufactures = ApiClient::callAuctionApi($manufcturesQuery, true);

        $models = [];
        if($request->manufacturer) {
            $modelQuery = "SELECT DISTINCT MODEL_NAME FROM main WHERE marka_name='" . $request->manufacturer. "' ORDER BY MODEL_NAME ASC";
            $models = ApiClient::callAuctionApi($modelQuery, true);
        }

        $years = [];
        $chassisNumbers = [];
        $engineCapacity = [];
        $colorQuery = [];
        if($request->manufacturer && $request->model) {
            // get years
            $yearsQuery = "SELECT DISTINCT year FROM main WHERE marka_name='" . $request->manufacturer . "' AND MODEL_NAME='" . $request->model . "' ORDER BY year DESC";
            $years = ApiClient::callAuctionApi($yearsQuery, true);

            // get chassis number
            $chassisNoQuery = "SELECT DISTINCT kuzov FROM main WHERE marka_name='" . $request->manufacturer . "' AND MODEL_NAME='" . $request->model . "' ";

            // get engine capacity
            $engineCapacityQuery = "SELECT DISTINCT eng_v FROM main WHERE marka_name='" . $request->manufacturer . "' AND model_name='" . $request->model . "' ";

            // get colors
            $vColorQuery = "SELECT DISTINCT COLOR FROM main WHERE marka_name='" . $request->manufacturer . "' AND model_name='" . $request->model . "' ";

            if($request->year_from == $request->year_to) {
                $chassisNoQuery .= "AND year = '" . $request->year_from . "' ";

                $engineCapacityQuery .= "AND year = '" . $request->year_from . "' ";

                $vColorQuery .= "AND year = '" . $request->year_from . "' ";
            } else if($request->year_from && $request->year_to) {
                $chassisNoQuery .= "AND year BETWEEN '" . $request->year_from . "' AND '" . $request->year_to . "' ";

                $engineCapacityQuery .= "AND year BETWEEN '" . $request->year_from . "' AND '" . $request->year_to . "' ";

                $vColorQuery .= "AND year BETWEEN '" . $request->year_from . "' AND '" . $request->year_to . "' ";
            }

            $chassisNoQuery .="ORDER BY kuzov ASC";

            $chassisNumbers = ApiClient::callAuctionApi($chassisNoQuery, true);

            if($request->chassis) {
                $engineCapacityQuery .= "AND kuzov = '" . $request->chassis . "' ";

                $vColorQuery .= "AND kuzov = '" . $request->chassis . "' ";
            }

            if ($request->engine) {
                $vColorQuery .= "AND eng_v = '" . $request->engine . "' ";
            }

            $engineCapacityQuery .="order by eng_v ASC";

            $engineCapacity = ApiClient::callAuctionApi($engineCapacityQuery, true);

            $vColorQuery .=" order by COLOR ASC";
            $colorQuery = ApiClient::callAuctionApi($vColorQuery, true);
        }

        // $vehiclesList = [];
        // if($request->search == 'true') {
            // dd( $requestData);
            $vehiclesList = $this->getVehicleList($request);
        // }

        return Inertia::render('Live_auction/index', ['requestQuery'=> $requestData, 'auctionDates'=>$auctionDates, 'manufactures'=>$manufactures, 'models'=>$models, 'years'=>$years, 'chassisNumbers'=>$chassisNumbers, 'engineCapacity'=>$engineCapacity, 'colorQuery'=>$colorQuery, 'vehiclesList'=>$vehiclesList]);
    }
    public function service()
    {
        // dd('test');
        return Inertia::render('Services/index');
    }
    public function available(Request $request)
    {
        $requestData = $request->all();
        $vehicleTypes = Type::where(['status'=> 1])->with('media')->get();
        $manufacturers = Manufacture::where(['status'=> 1])->with('media')->get();
        $models = VehicleModel::where(['status'=> 1])->get();

        // top filteration
        $vehiclesFilter = Vehicle::where(['status' => 1]);

        
        $minYear = clone $vehiclesFilter; 
        $minYear = $minYear->min('year');
        $maxYear = clone $vehiclesFilter;
        $maxYear =  $maxYear->max('year');
        
        $years = range($minYear, $maxYear);
        $years = array_filter($years);

        $minMileage = clone $vehiclesFilter;
        $minMileage = $minMileage->min('mileage');
        $maxMileage = clone $vehiclesFilter;
        $maxMileage = $maxMileage->max('mileage');

        // vehicle list
        $vehicles = Vehicle::where(['status'=>1]);
        if($request->brand){

            if(is_array($request->brand)) {
                $vehicles = $vehicles->whereIn('manufacture_id',$request->brand);
            }else {
                $vehicles = $vehicles->where('manufacture_id',$request->brand);
            }
        }
        if($request->type) {
            if(is_array($request->type)) {
                $vehicles = $vehicles->whereIn('vehicle_type_id',$request->type);
            } else {
                $vehicles = $vehicles->where('vehicle_type_id',$request->type);
            }
        }
        if($request->min_mileage && $request->max_mileage) {
            $vehicles = $vehicles->whereBetween('mileage', [$request->min_mileage, $request->max_mileage]);
        }
        if($request->year && (!$request->form_year && !$request->to_year)) {
            $vehicles = $vehicles->where('year', $request->year);
        }
        if($request->form_year && $request->to_year) {
            $vehicles = $vehicles->whereBetween('year', [$request->form_year, $request->to_year]);
        }
        $vehicles = $vehicles->with('media','vehicleType', 'manufacture', 'vehicleModel')->get();
        
        return Inertia::render('Available_stock/index', ['vehicleTypes'=> $vehicleTypes, 'manufacturers'=>$manufacturers, 'models'=> $models, 'vehicles'=>$vehicles,'years'=>$years, 'minMileage'=>$minMileage, 'maxMileage'=>$maxMileage, 'requestQuery'=> $requestData]);
    }
    public function faq()
    {
        // dd('test');
        return Inertia::render('FAQ/index');
    }
    public function testimonials()
    {
        // dd('test');
        return Inertia::render('Testimonials/index');
    }
    public function contact()
    {
        // dd('test');
        return Inertia::render('Contact_us/index');
    }
    public function login()
    {
        // dd('test');
        return Inertia::render('Login/index');
    }
    public function register()
    {
        // dd('test');
        return Inertia::render('Register/index');
    }
    public function signup()
    {
        // dd('test');
        return Inertia::render('Login/index');
    }


    public function product(Request $request)
    {
        if(!$request->id) {
            return redirect()->back();
        }
        $vehicle = Vehicle::with('manufacture', 'vehicleModel','vehicleType','exColor','inColor', 'media')->find($request->id);
       
       $features  = json_decode($vehicle->features, true);
       $features  = json_decode($features, true);
       $features = Feature::whereIn('id',$features??[])->get();
    //    dd($features);
        return Inertia::render('Product_view/index', ['vehicle' => $vehicle, 'features'=>$features]);
    }



    public function forgotpassword()
    {
        // dd('test');
        return Inertia::render('Forgot_password/index');
    }
    public function profile()
    {
        // dd('test');
        return Inertia::render('User_profile/index');
    }

    public function getVehicleList(Request $request) {
        $query = "SELECT * FROM main WHERE 1=1 ";

        if($request->search == 'true') {
            if ($request->manufacturer) {
                $query .= "AND marka_name = '" . $request->manufacturer . "' ";
            }
    
            if ($request->model) {
                $query .= "AND model_name = '" . $request->model . "' ";
            }
    
            // if ($request->year_to == 0 && $request->year_from != 0) {
            //     $query .= "AND year >= '" . $request->year_from . "' ";
            // } else if ($request->year_from == 0 && $request->year_to != 0) {
            //     $query .= "AND year <= '" . $request->year_to . "' ";
            // } else if ($request->year_from != 0 && $request->year_to != 0) {
            //     $query .= "AND year >= '" . $request->year_from . "' AND year <= '" . $request->year_to . "' ";
            // }
    
            // if ($request->chassis) {
            //     $query .= "AND kuzov = '" . $request->chassis . "' ";
            // }
    
            // if ($request->engine) {
            //     $query .= "AND eng_v = '" . $request->engine . "' ";
            // }
    
            // if ($request->color) {
            //     $query .= "AND COLOR = '" . $request->color . "' ";
            // }
    
            // if ($request->lot_no) {
            //     $query .= "AND LOT = '" . $request->lot_no . "' ";
            // }
    
            // if ($request->available_days) {
            //     $query .= "AND AUCTION_DATE LIKE = '%" . $request->available_days . "%' ";
            //     // TODO: filter auctio for multiple available dates
            // }
        }

        $page = $request->page ?? 1;
        $pageOffset = ($page -1) * 10;

        $query.=' ORDER BY year DESC LIMIT ' . $pageOffset . ',10';

        $vehiclesList = ApiClient::callAuctionApi($query);

        return $vehiclesList;
    }

    public function getVehicleStatsList(Request $request) {

        $query = "SELECT * FROM stats WHERE 1=1 ";
        if ($request->manufacture) {
            $query .= "AND marka_name = '" . $request->manufacture . "' ";
        }
        
        if ($request->model) {
                $query .= "AND model_name = '" . $request->model . "' ";
            }

        if ($request->chassis_no) {
            $query .= "AND kuzov = '" . $request->chassis_no . "' ";
        }

        if ($request->engine) {
            $query .= "AND eng_v = '" . $request->engine . "' ";
        }

        if ($request->year) {
            $query .= "AND year = '" . $request->year . "' ";
        }

        if ($request->rate) {
            $query .= "AND rate = '" . $request->rate. "' ";
        }

        $page = $request->page > 0 ? $request->page: 1;
        $pageOffset = ($page - 1) * 10;

        $query.="ORDER BY auction_date DESC LIMIT " . $pageOffset . ",10";

        // dd($query);
        $vehiclesStatList = ApiClient::callAuctionApi($query);

        return $vehiclesStatList;
    }
}
