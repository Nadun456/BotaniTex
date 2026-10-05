<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\ApiClient\ApiClient;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuctionController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $query = "SELECT * FROM main WHERE 1=1 ";
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

            $page = $request->page ?? 1;
            $pageOffset = ($page - 1) * 10;

            $query .= ' ORDER BY year DESC LIMIT ' . $pageOffset . ',10';

            $vehiclesList = ApiClient::callAuctionApi($query);

            return $this->successResponse($vehiclesList, 'success');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th, 500);
        }
    }

    public function details($id) {
        try {
            $query = "SELECT * FROM main WHERE id = '" . $id . "' order by marka_name ASC";
            $details = ApiClient::callAuctionApi($query, true);
            return $this->successResponse($details, 'success');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th, 500);
        }
    }

    public function manufactures()
    {
        try {
            $manufcturesQuery = "SELECT DISTINCT marka_name, images FROM main ORDER BY marka_name ASC";;
            $manufactures = ApiClient::callAuctionApi($manufcturesQuery, true);
            return $this->successResponse($manufactures, 'success');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th, 500);
        }
    }

    public function models(Request $request)
    {
        try {
            $models = [];
            if ($request->manufacturer) {
                $modelQuery = "SELECT DISTINCT MODEL_NAME FROM main WHERE marka_name='" . $request->manufacturer . "' ORDER BY MODEL_NAME ASC";
                $models = ApiClient::callAuctionApi($modelQuery, true);
            }
            return $this->successResponse($models, 'success');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th, 500);
        }
    }

    public function years(Request $request)
    {
        try {
            $years = [];
            if ($request->manufacturer && $request->model) {
                $yearsQuery = "SELECT DISTINCT year FROM main WHERE marka_name='" . $request->manufacturer . "' AND MODEL_NAME='" . $request->model . "' ORDER BY year DESC";
                $years = ApiClient::callAuctionApi($yearsQuery, true);
            }
            return $this->successResponse($years, 'success');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th, 500);
        }
    }

    public function chassisids(Request $request)
    {
        try {
            $chassisNumbers = [];
            if ($request->manufacturer && $request->model) {
                $chassisNoQuery = "SELECT DISTINCT kuzov FROM main WHERE marka_name='" . $request->manufacturer . "' AND MODEL_NAME='" . $request->model . "' ";

                if ($request->year_from == $request->year_to) {
                    $chassisNoQuery .= "AND year = '" . $request->year_from . "' ";
                } else if ($request->year_from && $request->year_to) {
                    $chassisNoQuery .= "AND year BETWEEN '" . $request->year_from . "' AND '" . $request->year_to . "' ";
                }

                $chassisNoQuery .= "ORDER BY kuzov ASC";
                $chassisNumbers = ApiClient::callAuctionApi($chassisNoQuery, true);
            }
            return $this->successResponse($chassisNumbers, 'success');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th, 500);
        }
    }

    public function capacity(Request $request)
    {
        try {
            $engineCapacity = [];
            if ($request->manufacturer && $request->model) {
                $engineCapacityQuery = "SELECT DISTINCT eng_v FROM main WHERE marka_name='" . $request->manufacturer . "' AND model_name='" . $request->model . "' ";

                if ($request->year_from == $request->year_to) {
                    $engineCapacityQuery .= "AND year = '" . $request->year_from . "' ";
                } else if ($request->year_from && $request->year_to) {
                    $engineCapacityQuery .= "AND year BETWEEN '" . $request->year_from . "' AND '" . $request->year_to . "' ";
                }

                if ($request->chassis) {
                    $engineCapacityQuery .= "AND kuzov = '" . $request->chassis . "' ";
                }
                $engineCapacityQuery .= "order by eng_v ASC";

                $engineCapacity = ApiClient::callAuctionApi($engineCapacityQuery, true);
            }
            return $this->successResponse($engineCapacity, 'success');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th, 500);
        }
    }

    public function dates()
    {
        try {
            $auctionDatesQuery = "SELECT auction_date FROM main GROUP BY DATE_FORMAT(auction_date,'%Y-%m-%d')";
            $auctionDates = ApiClient::callAuctionApi($auctionDatesQuery);
            return $this->successResponse($auctionDates, 'success');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th, 500);
        }
    }
}
