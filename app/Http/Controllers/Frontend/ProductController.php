<?php


namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BikeCategory;
use App\Models\BikeManufacture;
use App\Models\BikeModel;
use App\Models\BikeProduct;
use App\Models\Inquiry;
use App\Models\Manufacture;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductController extends Controller
{

    public function ProductDetailsView($id)
    {

        $product = BikeProduct::with('media')->find($id);

        $recentProduct = BikeProduct::with('media')->where(['status' => 1, 'featured' => 'best_selling'])->limit(4)->get();
        // dd($recentProduct);

        $manufacture = BikeManufacture::find($product->manufacture);

        // dd($product);

        return Inertia::render('ProductView/index', ['product' => $product, 'manufacture' => $manufacture, 'recentProduct' => $recentProduct]);
    }

    public function CategoryProductDetailsView($id)
    {

        $product = BikeProduct::with('media')->where(['categories' => $id])->get();
        // dd($product);

        return Inertia::render('CategoryProducts/Index', ['product' => $product]);
    }

    public function AllProduct(Request $request)
    {
        //  dd($request->all());

        $allProduct = BikeProduct::with('media')->where('status', 1);

        if ($request->model) {
            $allProduct = $allProduct->where('model', $request->model);
        }

        if ($request->manufacture) {
            $allProduct = $allProduct->where('manufacture', $request->manufacture);
        }

        if (!empty($request->selectedCategories)) {
            $allProduct = $allProduct->where(function ($query) use ($request) {
                foreach ($request->selectedCategories as $category) {
                    $query->orWhere('categories', 'LIKE', '%' . $category . '%');
                }
            });
        }


        if (!empty($request->selectedModels)) {
            $allProduct = $allProduct->where(function ($query) use ($request) {
                foreach ($request->selectedModels as $model) {
                    $query->orWhere('model', 'LIKE', '%' . $model . '%');
                }
            });
        }


        if (!empty($request->selectedManufactures)) {
            $allProduct = $allProduct->where(function ($query) use ($request) {
                foreach ($request->selectedManufactures as $manufacture) {
                    $query->orWhere('manufacture', 'LIKE', '%' . $manufacture . '%');
                }
            });
        }

        $allProduct = $allProduct->get();


        $manufacture = BikeManufacture::all();
        $model = BikeModel::all();
        $category = BikeCategory::all();


        return Inertia::render('AllProducts/index', [
            'allProduct' => $allProduct,
            'manufacture' => $manufacture,
            'model' => $model,
            'category' => $category,
        ]);
    }

    public function BrowsProduct(Request $request)
    {
        // dd($request->all());
        if ($request->has('manufacture')) {
           
            $browsManufacture = BikeProduct::with('media')
                ->where('manufacture', $request->manufacture)
                ->get();
            $name = BikeManufacture::where('id', $request->manufacture)->first()->name ?? 'Unknown Manufacture';
        } elseif ($request->has('model')) {
            
            $browsManufacture = BikeProduct::with('media')
                ->where('model', $request->model)
                ->get();
            $name = BikeModel::where('id', $request->model)->first()->name ?? 'Unknown Model';
        }

       
        return Inertia::render('BrowsProduct/Index', [
            'browsManufacture' => $browsManufacture,
            'name' => $name
        ]);
    }

    public function make(){

        $manufacture = BikeManufacture::with('media')->where(['status' => 1])->get();
        // dd($manufacture->all());

        return Inertia::render('Make/Index',['manufacture'=>$manufacture]);

    }

    public function model(){

        $model = BikeModel::with('media')->where(['status' => 1])->get();
        // dd($model->all());

        return Inertia::render('Model/Index',['model'=>$model]);

    }


    
       
    
}
