<?php

namespace App\Http\Controllers;

use App\Models\BikeCategory;
use App\Models\BikeColor;
use App\Models\BikeManufacture;
use App\Models\BikeModel;
use App\Models\BikeProduct;
use App\Models\Country;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Yajra\DataTables\Facades\DataTables;

class BikeProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('BikeProduct/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $country = Country::all();
        $categories = BikeCategory::all();
        $color = BikeColor::all();
        $model = BikeModel::all();
        $manufacture = BikeManufacture::all();
        // dd($manufacture->all());
        return Inertia::render('BikeProduct/CreateUpdate', ['country' => $country, 'categories' => $categories, 'color' => $color, 'model' => $model, 'manufacture' => $manufacture]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'sku' => 'required',
            'price' => 'nullable',
            'quantity' => 'required',
            'minimum_order_quantity' => 'required',
            'description' => 'required',
            'specification' => 'required',
            'country' => 'required',
            'categories' => 'required',
            'color' => 'required',
            'model' => 'required',
            'manufacture' => 'required',
            'status' => 'required',
            'comfortable' => 'required',
            'uploaded_Images.*.file' => 'required',
        ]);


        try {
            DB::beginTransaction();

            $product = new BikeProduct();
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->sku = $request->sku;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->minimum_order_quantity = $request->minimum_order_quantity;
            $product->description = $request->description;
            $product->specification = $request->specification;
            $product->country = json_encode($request->country);
            $product->categories = $request->categories;
            $product->color = $request->color;
            $product->model = $request->model;
            $product->manufacture = $request->manufacture;
            $product->status = $request->status;
            $product->comfortable = $request->comfortable;
            $product->featured = $request->featured;

            $product->save();

            $folderName = 'Product_' . $product->id;

            // Handle uploaded images
            if ($request->has('uploaded_Images')) {
                foreach ($request->uploaded_Images as $image) {
                    // Save each uploaded image to the media collection
                    $product->addMedia($image['file'])
                        ->withCustomProperties(['type' => 'main'])
                        ->toMediaCollection($folderName . '/uploaded_Images');
                }
            }

            DB::commit();

            return redirect()->route('product.index');
        } catch (Exception $ex) {
            dd($ex);
            DB::rollBack();
            return abort(500);
        }
    }

    public function getData()
    {
        $users = BikeProduct::all();
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
            // ->addColumn('image', function ($row) {
            //     if (count($row->media) > 0) {
            //         $image = '<img src="' . $row->media[0]->original_url . '" height="25"/>';
            //     } else {
            //         $image = "No Image";
            //     }

            //     return $image;
            // })
            ->rawColumns(['check', 'action', 'status', 'name', 'price', 'quantity'])
            ->make(true);
    }


    /**
     * Display the specified resource.
     */
    public function show(BikeProduct $bikeProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $bikeProduct = BikeProduct::with('media')->find($id);


        // $images = $bikeProduct->getMedia('Product_' . $bikeProduct->id . '/uploaded_Images')->map(function ($media) {
        //     return [
        //         'id' => $media->id,
        //         'url' => $media->getUrl(),
        //         'name' => $media->file_name,
        //     ];
        // });

        // dd($bikeProduct);

        $country = Country::all();
        $categories = BikeCategory::all();
        $color = BikeColor::all();
        $model = BikeModel::all();
        $manufacture = BikeManufacture::all();


        return Inertia::render('BikeProduct/CreateUpdate', [
            'bikeProduct' => $bikeProduct,
            'country' => $country,
            'categories' => $categories,
            'color' => $color,
            'model' => $model,
            'manufacture' => $manufacture,

        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $product = BikeProduct::find($request->id);
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->sku = $request->sku;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->minimum_order_quantity = $request->minimum_order_quantity;
            $product->description = $request->description;
            $product->specification = $request->specification;
            $product->country = json_encode($request->country);
            $product->categories = $request->categories;
            $product->color = $request->color;
            $product->model = $request->model;
            $product->manufacture = $request->manufacture;
            $product->status = $request->status;
            $product->comfortable = $request->comfortable;
            $product->featured = $request->featured;
            $product->save();

            $folderName = 'Product_' . $product->id;

            if ($request->uploaded_Images) {
                foreach ($request->uploaded_Images as $image) {
                    if (is_array($image['file']) && isset($image['file']['original_url'])) {

                        $urlParts = explode('/', $image['file']['original_url']);
                        $uuid = $urlParts[count($urlParts) - 2];
                        $fileName = $urlParts[count($urlParts) - 1];

                        // Find the media record by UUID or file name
                        $existingMedia = Media::where('uuid', $uuid)
                            ->where('file_name', $fileName)
                            ->first();
                        // dd($existingMedia);

                        // If the file doesn't exist, add it to the media collection
                        if (!$existingMedia) {
                            $product->addMedia($image['file'])
                                ->withCustomProperties(['type' => 'main'])
                                ->toMediaCollection($folderName . '/uploaded_Images');
                        }
                    } else {
                        $product->addMedia($image['file'])
                            ->withCustomProperties(['type' => 'main'])
                            ->toMediaCollection($folderName . '/uploaded_Images');
                    }
                }
            }
            DB::commit();

            return redirect()->route('product.index');
        } catch (Exception $ex) {
            dd($ex);
            DB::rollBack();
            Log::error($ex);
            abort(500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function updateStatus(Request $request)
    {
        // dd($request->all());
        try {
            $user = BikeProduct::find($request->id);
            if ($request->status == 0) {
                $user->status = 1;
            } else {
                $user->status = 0;
            }
            $user->save();

            return redirect()->route('product.index');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // dd($request->all());
        try {
            BikeProduct::destroy([$request->ids]);
            return redirect()->route('product.index');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }

    public function removeImage(Request $request)
    {

        // Split the image URL to get the UUID and file name
        $urlParts = explode('/', $request->imageUrl);
        $uuid = $urlParts[count($urlParts) - 2]; // Assuming the UUID is in the second last part
        $fileName = $urlParts[count($urlParts) - 1]; // The last part is the file name

        // Find the media record by UUID or file name
        $media = Media::where('uuid', $uuid)
            ->where('file_name', $fileName)
            ->first();
        // dd($media);
        if ($media) {
            // Delete the media from the product
            $media->delete(); // This removes the file from storage and the database

            return redirect()->back();
        }
    }
}
