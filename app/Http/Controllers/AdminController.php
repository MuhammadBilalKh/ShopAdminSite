<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Tag;
use App\Models\City;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\MajorArea;
use App\Models\MinorArea;
use App\Models\OrderHistory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductHasTag;
use App\Models\RecentActivity;
use App\Models\ShippingMethod;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class AdminController extends Controller
{
    public function admin_sign_in(Request $request){
          if($request->isMethod("POST")){
             $request->validate([
                'email' => "required|email",
                'password' => "required"
             ]);

             $user = Auth::attempt(["email"=> $request->email, "password"=> $request->password]);

             if($user){
                if(Auth::user()->status == STATUS_INACTIVE){
                    return redirect()->back()->withErrors(['email'=> 'Your Account Have Been Marked As Inactive'])->withInput();                
                }

                return redirect()->route('admin.show_admin_dashboard');
             } else {
                return redirect()->back()->withErrors(['email'=> 'Invalid Credentials'])->withInput();
             }
          }

          return view('admin.sign-in');
    }

    public function show_admin_dashboard(Request $request){
        $totalCategories = Category::count();
        $totalOrders = Order::where("order_status", ORDER_STATUS_PENDING)->count();
        $totalProducts = Product::count();
        $recentOrders = Order::with("getOrderBy")->where(['order_status' => ORDER_STATUS_PENDING])->orderbyDesc("order_id")->limit(10)->get();
        $totalRevenue = Order::where('order_status', ORDER_STATUS_PROCESSED)->whereBetween('created_at', [Carbon::now()->startOfMonth(),Carbon::now()->endOfMonth(),])->sum('total_amount');

        $recentActivties = RecentActivity::orderByDesc("created_at")->limit(10)->get();
        return view('admin.dashboard', compact('recentOrders','totalCategories', 'totalOrders', 'totalProducts', 'recentActivties', 'totalRevenue'));
    }

    public function product_category_lists(Request $request){
        if($request->ajax()){

        }

        $categories = Category::with("getCreatedBy", "getUpdatedBy")->withCount("products")->orderByDesc("category_id")->paginate(10);
        return view('admin.product_category.index', compact('categories'));
    }

    public function product_lists(Request $request){
        $products = new Product();

        if($request->ajax()){
            $products = $products->with("getProductCategory")->newQuery();

            if($request->filled("product_name")){
                $products->where(['product_name' => $request->product_name]);
            }

            if($request->filled("category")){
                $products->where(["category_id" => $request->category]);
            }

            if($request->filled("price")){
                $products->where(['regular_price' => $request->price]);
            }

            if($request->filled("quantity")){
                $products->where(['quantity' => $request->quantity]);
            }

            if($request->filled("stock_status")){
                if($request->stock_status == 'out-of-stock'){
                    $products->where(['quantity' => 0]);
                } else if($request->stock_status == 'low-stock'){
                    $products->where('quantity', "<", LOW_STOCK_QUANTITY);
                    $products->where('quantity', '>', 0);
                } else {
                    $products->where('quantity', '>', LOW_STOCK_QUANTITY);
                }
            }

            $dataTables = new DataTables();
            return $dataTables->eloquent($products)
                ->addIndexColumn()
                ->addColumn("actions", function($data){
                    return html()->button("")->attributes(['data-bs-toggle' => "modal", 'data-bs-target' => "#productModal"])->class("btn-action edit edit-btn")->html("<i class='ri-pencil-line'></i>")->id(Crypt::encrypt($data->product_id));
                })
                ->addColumn("stock_status", function($data){
                    if($data->quantity < LOW_STOCK_QUANTITY && $data->quantity > 0){
                        return "<span class='badge bg-warning'>Low Stock</span>";
                    } else if($data->quantity == 0){
                        return "<span class='badge bg-danger'>Out of Stock</span>";
                    } else if($data->quantity > LOW_STOCK_QUANTITY){
                        return "<span class='badge bg-success'>In Stock</span>";
                    }
                })
                ->rawColumns(['actions', "stock_status"])
                ->make(true);
        }

        $totalProducts = $products->count();
        $in_stock_products = $products->where("quantity", ">", LOW_STOCK_QUANTITY)->count();
        $out_stock_products = $products->where("quantity","<=", LOW_STOCK_QUANTITY)->count();
        $zero_stock_products = $products->where("quantity", 0)->count();

        $categories = Category::where("status", STATUS_ACTIVE)->pluck("category_name", "category_id");

        return view('admin.products.products', [
            'totalProducts' => $totalProducts,
            'in_stock_products' => $in_stock_products,
            'out_stock_products' => $out_stock_products,
            'zero_stock_products' => $zero_stock_products, 
            "categories" => $categories
        ]);
    }

    public function create_category(Request $request){
        $category = new Category();

        if($request->isMethod("POST")){
            $request->validate([
                'category_name' => "required|unique:categories,category_name",
                'category_status' => "required|numeric"
            ]);

            $category->category_name = $request->category_name;
            $category->category_description = $request->category_description;
            $category->created_by = Auth::user()->user_id;
            $category->status = $request->category_status;

            $category->save();

            RecentActivity::create([
                'model_id' => $category->category_id,
                'activity_description' => Auth::user()->name." Have Created The New Product Category ".$category->category_name,
                'model_class' => Category::class
            ]);

            return redirect()->route('admin.product_category_lists')->with("success", "Product Category Added Successfully.");
        }

        return view('admin.product_category.create')->render();
    }

    public function edit_product_category($id, Request $request){
        try {
            $categoryData = Category::findorFail(Crypt::decrypt($id));
            
            if($request->isMethod("POST")){
                $request->validate([
                    "category_name" => [
                        "required",
                        Rule::unique("categories", "category_name")
                            ->ignore($categoryData->category_id, "category_id"),
                    ],
                    "category_status" => "required|numeric"
                ]);

                $categoryData->category_name = $request->category_name;
                $categoryData->category_description = $request->category_description;
                $categoryData->updated_by = Auth::user()->user_id;
                $categoryData->status = $request->category_status;
                
                $categoryData->save();

                RecentActivity::create([
                    'model_class' => Category::class,
                    'model_id' => $categoryData->category_id,
                    'activity_description' => Auth::user()->name." Have Updated The Product Category ".$categoryData->category_name
                ]);
                
                return redirect()->route('admin.product_category_lists')->with("success", "Product Category Updated Successfully.");
                
                }
        } catch(DecryptException $e) {
            return redirect()->route('admin.product_category_lists')->with('error', $e->getMessage());
        }

        return view('admin.product_category.edit', compact('categoryData'));
    }

    public function delete_product_category(Request $request, $id){
        try {
            $id = Crypt::decrypt($request->id);
            $category = Category::findorFail($id);
            $product_count = Product::where(['category_id' => $id])->count();
            $decID = Crypt::encrypt($id);
            
            if($request->isMethod('POST')){
                if($product_count > 0){
                    return redirect()->back()->with("error", "Invalid Category ID");
                }

                $categoryID = $category->category_id;
                $categoryname = $category->category_name;
                $category->delete();

                RecentActivity::create([
                    'model_class' => Category::class,
                    'model_id' => $categoryID,
                    'activity_description' => Auth::user()->name." Have Deleted The Product Category ".$categoryname
                ]);

                return redirect()->route('admin.product_category_lists')->with('success','Category Deleted Successfully.');
            }
        } catch(DecryptException $e) {
            return redirect()->route('admin.product_category_lists')->with('error', $e->getMessage());
        }

        return view('admin.product_category.delete', compact("product_count", "decID"));
    }

    public function export_product_category(){
        ini_set('memory_limit', '16384M');
        ini_set('max_execution_time', '900');

        $categoryData = Category::with('getCreatedBy', "getUpdatedBy")->withCount("products")->get()->toArray();
        $csvHeaders = ["S.No", "Category Name", "Category Descritpion", "Status", "Created By", "Updated By"];

        $fileName = 'ProductCategoriesList-' . now()->format('Y-m-d_H-i-s') . '.csv';

        $handle = fopen($fileName, 'w');

        if ($handle === false) {
            die("Can't open file");
        }

        fputcsv($handle, $csvHeaders);
        foreach($categoryData as $key => $value){
            fputcsv($handle, [
                $key + 1,
                $value["category_name"],
                $value["category_description"],
                $value["status"] == STATUS_ACTIVE ? "Active" : "In-Active",
                $value["get_created_by"]["name"],
                $value["get_updated_by"]["name"]
            ]);
        }

        fclose($handle);

        if (isset($fileName)) {
            header('Content-Type:text/plain; charset=ISO-8859-15');
            header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
            header('Content-Length: ' . filesize($fileName));
            readfile($fileName);
        }
        
        unset($fileName);
        exit(0);
    }

    public function create_product(Request $request){
       if($request->isMethod("POST")){
          $request->validate([
                'product_name' => 'required',
                'product_category' => 'required|numeric|exists:categories,category_id',
                'product_price' => "required|numeric|min:1",
                'sale_price' => "nullable|numeric|min:1",
                'stock_quantity' => "required|numeric|min:1",
                'product_image' => "required|image",
                'product_description' => "required",
                'product_tags' => "required"
            ], [
                'product_name.required' => "Product name is required.",
                'product_category.required' => "Please select a product category.",
                'product_category.numeric' => "The selected category format is invalid.",
                'product_category.exists' => "The selected category does not exist in our system.",
                'product_price.required' => "Please enter the product price.",
                'product_price.numeric' => "The price must be a valid number.",
                'product_price.min' => "The product price must be at least 1.",
                'sale_price.numeric' => "The sale price must be a valid number.",
                'sale_price.min' => "The sale price must be at least 1.",
                'stock_quantity.required' => "Please specify the initial stock quantity.",
                'stock_quantity.numeric' => "Stock quantity must be a whole number.",
                'stock_quantity.min' => "Stock quantity must be at least 1 unit.",
                'product_image.required' => "Please upload a product image.",
                'product_image.image' => "The uploaded file must be an image (JPEG, PNG, JPG, or WebP).",
                'product_description.required' => "Please provide a product description.",
                'product_tags.required' => "Please add at least one tag for this product."
            ]);

          try {
                DB::beginTransaction();
                    
                $product = new Product();

                $productProfileImage = '';

                if($request->hasFile("product_image")){
                    $imageFile = $request->file("product_image");
                    $productProfileImage = $imageFile->hashName();
                    $imageFile->move(public_path('product_profile_image'), $productProfileImage);
                }
                    
                $product->product_name = $request->input("product_name");
                $product->description = $request->input("product_description");
                $product->category_id = $request->input("product_category");
                $product->unique_product_id = Str::slug($request->product_name)."-".Str::uuid();
                $product->regular_price = $request->product_price;
                $product->quantity = $request->stock_quantity;
                $product->sales_price = $request->sale_price;
                $product->product_profile_image = $productProfileImage;
                $product->is_new = $request->input("is_new", 0);
                $product->is_featured = $request->input("is_featured", 0);
                $product->created_by = Auth::user()->user_id;

                $product->save();
                
                $tagsArr = explode(",", $request->product_tags);
                
                foreach( $tagsArr as $tag ){
                    $tags = Tag::create([
                        'tag_name' => $tag
                    ]);
                    
                    ProductHasTag::create([
                        'product_id' => $product->product_id,
                        'tag_id' => $tags->product_tag_id
                    ]);
                }

                RecentActivity::create([
                    'model_class' => Product::class,
                    'model_id' => $product->product_id,
                    'activity_description' => Auth::user()->name." Have Created The Product ".$product->product_name." (".$product->unique_product_id.")"
                ]);
                        
                DB::commit();
                return response()->json(['status' => REQUEST_PROCESSED_SUCCESSFULLY, 'message' => "Product Created Successfully."]);

            } catch(Exception $e){
                DB::rollBack();
                return response()->json(['status' => 0, 'message' => $e->getMessage()]);
            }

       }

       $categories = Category::where(['status' => STATUS_ACTIVE])->pluck("category_name", "category_id")->toArray();
       return view('admin.products.create', compact('categories'))->render();
    }

    public function edit_product(Request $request, $id){
        $decID = Crypt::decrypt($id);

        $productData = Product::with("getproductCategory", "tags","tags.getTags")->findOrFail($decID);

        if($request->isMethod("POST")){
            $request->validate([
                'product_name' => 'required',
                'product_category' => 'required|numeric|exists:categories,category_id',
                'product_price' => "required|numeric|min:1",
                'sale_price' => "nullable|numeric|min:1",
                'stock_quantity' => "required|numeric|min:1",
                'product_image' => "nullable|image",
                'product_description' => "required",
                'product_tags' => "required"
            ], [
                'product_name.required' => "Product name is required.",
                'product_category.required' => "Please select a product category.",
                'product_category.numeric' => "The selected category format is invalid.",
                'product_category.exists' => "The selected category does not exist in our system.",
                'product_price.required' => "Please enter the product price.",
                'product_price.numeric' => "The price must be a valid number.",
                'product_price.min' => "The product price must be at least 1.",
                'sale_price.numeric' => "The sale price must be a valid number.",
                'sale_price.min' => "The sale price must be at least 1.",
                'stock_quantity.required' => "Please specify the initial stock quantity.",
                'stock_quantity.numeric' => "Stock quantity must be a whole number.",
                'stock_quantity.min' => "Stock quantity must be at least 1 unit.",
                'product_image.image' => "The uploaded file must be an image (JPEG, PNG, JPG, or WebP).",
                'product_description.required' => "Please provide a product description.",
                'product_tags.required' => "Please add at least one tag for this product."
            ]);

            try {

                $uniqueImageName = "";
                
                if($request->hasFile("product_image")){
                    $imageFile = $request->file("product_image");
                    $uniqueImageName = $imageFile->hashName();
                    $imageFile->move(public_path('product_profile_image'), $uniqueImageName);
                } else {
                    $uniqueImageName = $productData->product_profile_image;
                }
                
                DB::beginTransaction();
                
                $productTagsIDs = ProductHasTag::where(['product_id' => $productData->product_id])->pluck("tag_id");
                
                Tag::whereIn("product_tag_id", $productTagsIDs)->delete();
                ProductHasTag::where(['product_id' => $productData->product_id])->delete();
                
                $productData->product_name = $request->input("product_name");
                $productData->description = $request->input("product_description");
                $productData->category_id = $request->input("product_category");
                $productData->unique_product_id = Str::slug($request->product_name)."-".Str::uuid();
                $productData->regular_price = $request->product_price;
                $productData->quantity = $request->stock_quantity;
                $productData->sales_price = $request->sale_price;
                $productData->product_profile_image = $uniqueImageName;
                $productData->is_new = $request->input("is_new", 0);
                $productData->is_featured = $request->input("is_featured", 0);
                $productData->updated_by = Auth::user()->user_id;

                $productData->save();
                
                $tagsArr = explode(",", $request->product_tags);
                
                foreach( $tagsArr as $tag ){
                    $tags = Tag::updateOrCreate([
                        'tag_name' => $tag
                    ]);
                    
                    ProductHasTag::create([
                        'product_id' => $productData->product_id,
                        'tag_id' => $tags->product_tag_id
                    ]);
                }

                RecentActivity::create([
                    'model_class' => Product::class,
                    'model_id' => $productData->product_id,
                    'activity_description' => Auth::user()->name." Have Updated The Product Detail ".$productData->product_name." (".$productData->unique_product_id.")"
                ]);

                DB::commit();
                return response()->json(['status' => REQUEST_PROCESSED_SUCCESSFULLY, 'message' => "Product Detail Updated Successfully."]);
                
                } catch(Exception $e) {
                    DB::rollBack();
                    return response()->json(['status' => 0, 'message' => $e->getMessage()]);
                }
        }

        $categories = Category::where(['status' => STATUS_ACTIVE])->pluck("category_name", "category_id")->toArray();
        return view('admin.products.edit', compact("productData", "categories"))->render();
    }

    public function export_all_products(){
        ini_set('memory_limit', '5120M');
        ini_set('max_execution_time', '900');

        $products = Product::with("getProductCategory", "getCreatedBy", "getUpdatedBy")->get();
        $csvHeaders = ["S.No", "Product ID", "Product Name", "Category", "Price", "On Sale Price (if any)", "Stock Quantity", "Stock Status", "New Product","Featured Product","Description","Created By", "Updated By", "Registered At", "Updated At"];

        $fileName = 'ProductsLists-' . now()->format('Y-m-d_H-i-s') . '.csv';
        $handle = fopen($fileName, "w") or die("can't open file");

        fputcsv($handle, $csvHeaders);

        foreach($products as $key => $value){
            
            $userString = $value->getCreatedBy->login_id . " - ". $value->getCreatedBy->user_role;
            $updatedUserString = $value->updated_by ? $value->getUpdatedBy->login_id." - ".$value->getUpdatedBy->user_role : '';
            
            fputcsv($handle, [
                $key + 1,
                $value->unique_product_id,
                $value->product_name,
                $value->getProductCategory->category_name,
                $value->regular_price,
                $value->sales_price,
                $value->quantity,
                $value->getProductQuantityLabel($value->quantity),
                $value->is_new == 1 ? "Yes" : "No",
                $value->is_featured == 1 ? "Yes" : "No",
                $value->description,
                $value->getCreatedBy->name." (".$userString.")",
                $value->updated_by ? $value->getUpdatedBy->name. " (".$updatedUserString.")" : '',
                $value->created_at,
                $value->updated_at
            ]);
        }

        fclose($handle);

        if (isset($fileName)) {
            header('Content-Type:text/plain; charset=ISO-8859-15');
            header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
            header('Content-Length: ' . filesize($fileName));
            readfile($fileName);
        }
        
        unset($fileName);
        exit(0);
    }

    public function view_recent_activities(){
        $recentActivities = RecentActivity::orderByDesc("created_at")->simplePaginate(10);
        return view('admin.recent_activities', compact('recentActivities'));
    }

    public function manage_cities(Request $request){
        $cityQuery = City::query();

        if($request->ajax()){
            $dt = new DataTables();

            if ($request->filled("city_name")) {
                $cityQuery->where('city_name', 'LIKE', '%' . $request->city_name . '%');
            }

            if ($request->filled("iata_code")) {
                $cityQuery->where('iata_code', 'LIKE', '%' . $request->iata_code . '%');
            }

            return $dt->eloquent($cityQuery)
                    ->addIndexColumn()
                    ->addColumn("actions", function($data){
                        return html()->button("")->attributes(['data-bs-toggle' => "modal", 'data-bs-target' => "#cityModal"])->class("btn-action float-end edit edit-btn")->html("<i class='ri-pencil-line'></i>")->id(Crypt::encrypt($data->city_id));
                    })
                    ->make(true);
        }

        return view('admin.city.cities');
    }

    public function create_city(Request $request){
        $city = new City();

        if($request->isMethod("POST")){
            $request->validate([
                'city_name' => "required|min:3|unique:cities,city_id",
                'iata_code' => "required|min:1|unique:cities,city_id"
            ],[
                'city_name.required' => "City Name Is Required",
                'iata_code.required' => "City Iata Code Is Required",
                'city_name.unique' => "City Name $request->city_name Already Exists"
            ]);
    
            $city->city_name = $request->city_name;
            $city->iata_code = $request->iata_code;

            $city->save();

            RecentActivity::create([
                'model_id' => $city->city_id,
                'model_class' => City::class,
                'activity_description' => Auth::user()->name. " Have Created The City: ".$city->city_name
            ]);

            return response()->json(['status' => REQUEST_PROCESSED_SUCCESSFULLY, 'message' => "City Created Successfully."]);
        }

        return view('admin.city.create');
    }

    public function edit_city(Request $request, $id){
        $decID = Crypt::decrypt($id);
        $cityData  = City::findOrFail($decID);

        if($request->isMethod('POST')){
            $request->validate([
                'city_name' => "required|min:3|unique:cities,city_id,$id,city_id",
                'iata_code' => "required|min:1|unique:cities,city_id,$id,city_id"
            ],[
                'city_name.required' => "City Name Is Required",
                'iata_code.required' => "City Iata Code Is Required",
                'city_name.unique' => "City Name $request->city_name Already Exists"
            ]);

            $cityData->city_name = $request->city_name;
            $cityData->iata_code = $request->iata_code;

            $cityData->save();

            RecentActivity::create([
                'model_id' => $cityData->city_id,
                'model_class' => City::class,
                'activity_description' => Auth::user()->name. " Have Updated The City: ".$cityData->city_name
            ]);

            return response()->json(['status' => REQUEST_PROCESSED_SUCCESSFULLY, 'message' => "City Updated Successfully."]);
        }

        return view('admin.city.edit', compact('cityData'));
    }

    public function export_cities(){
        ini_set('memory_limit', '16384M');
        ini_set('max_execution_time', '900');

        $categoryData = City::all()->toArray();
        $csvHeaders = ["S.No", "City Name","Iata Code"];

        $fileName = 'AllCities-' . now()->format('Y-m-d_H-i-s') . '.csv';

        $handle = fopen($fileName, 'w');

        if ($handle === false) {
            die("Can't open file");
        }

        fputcsv($handle, $csvHeaders);
        foreach($categoryData as $key => $value){
            fputcsv($handle, [
                $key + 1,
                $value["city_name"],
                $value["iata_code"],
            ]);
        }

        fclose($handle);

        if (isset($fileName)) {
            header('Content-Type:text/plain; charset=ISO-8859-15');
            header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
            header('Content-Length: ' . filesize($fileName));
            readfile($fileName);
        }
        
        unset($fileName);
        exit(0);
    }

    public function manage_major_areas(Request $request){
        if($request->ajax() ){
            $dt = new DataTables();

            $majorAreaQuery = MajorArea::with("getMajorAreaCity", "getCreatedBy")->newQuery();

            if($request->filled('major_area_name')){
                $majorAreaQuery->where('major_area_name', "LIKE", "%".$request->major_area_name."%");
            }

            if($request->filled('city_name')){
                $majorAreaQuery->where(['city_id'=> $request->city_name]);
            }

            $majorAreaQuery->orderbyDesc("created_at");
            return $dt->eloquent($majorAreaQuery)
                    ->addIndexColumn()
                    ->addColumn("actions", function($data){
                        return html()->button("")->attributes(['data-bs-toggle' => "modal", 'data-bs-target' => "#majorAreaModal"])->class("btn-action edit edit-btn")->html("<i class='ri-pencil-line'></i>")->id(Crypt::encrypt($data->major_area_id));  
                    })
                    ->rawColumns(['actions'])
                    ->make(true);
        }

        $cities = City::pluck("city_name", "city_id")->toArray();
        return view('admin.city.major_area.major_areas', compact('cities'));
    }

    public function create_major_area(Request $request){
        if($request->isMethod("POST") && $request->ajax() ){
            $request->validate([
                'major_area_name' => 'required|min:3',
                'major_area_city' => "required|exists:cities,city_id",
            ]);

            $cityData = City::findOrFail($request->major_area_city);
            $nMajorArea = MajorArea::create([
                'major_area_name' => $request->major_area_name,
                'city_id' => $request->major_area_city,
                'created_by' => Auth::user()->user_id
            ]);

            RecentActivity::create([
                'model_class' => MajorArea::class,
                'model_id' => $nMajorArea->major_area_id,
                'activity_description' => Auth::user()->name ." Have Created $nMajorArea->major_area_name Under The City ".$cityData->city_name 
            ]);

            return response()->json(['status' => REQUEST_PROCESSED_SUCCESSFULLY, 'message' => "Major Area Created Successfully"]);
        }

        if($request->filled('view_form') && $request->view_form == 1){
            return view('admin.city.major_area._form', [
                'cities' => City::pluck("city_name", "city_id")->toArray(),
                'action' => route('admin.submit_major_area'),
                'majorAreaData' => null
            ])->render();
        }
    }

    public function edit_major_area(Request $request, $id){
        $decID = Crypt::decrypt($id);
        $mjArea = MajorArea::findOrFail($decID);

        if($request->isMethod("POST")){
            $request->validate([
                'major_area_name' => 'required|min:3',
                'major_area_city' => "required|exists:cities,city_id",
            ]);

            $cityData = City::findOrFail($request->major_area_city);

            $mjArea->major_area_name = $request->major_area_name;
            $mjArea->city_id = $request->major_area_city;

            $mjArea->save();

            RecentActivity::create([
                'model_class' => MajorArea::class,
                'model_id' => $mjArea->major_area_id,
                'activity_description' => Auth::user()->name ." Have Updated The Major Area $mjArea->major_area_name Under The City ".$cityData->city_name 
            ]);

            return response()->json(['status' => REQUEST_PROCESSED_SUCCESSFULLY, 'message' => "Major Area Updated Successfully"]);
        }

        return view('admin.city.major_area._form', [
            'cities' => City::pluck("city_name", "city_id")->toArray(),
            'action' => route('admin.update_major_area', ['id' => Crypt::encrypt($decID)]),
            'majorAreaData' => $mjArea
        ])->render();
    }

    public function export_major_areas(){
    
        ini_set('memory_limit', '16384M');
        ini_set('max_execution_time', '900');

        $mjAreas = MajorArea::with("getCreatedBy", "getMajorAreaCity")->get();
        $csvHeaders = ["S.No", "Major Area", "City", "Created By"];

        $fileName = 'MajorAreasList-' . now()->format('Y-m-d-H-i-s') . '.csv';
        $handle = fopen($fileName, "w");

        fputcsv($handle, $csvHeaders);

        foreach ($mjAreas as $key => $value) {
            fputcsv($handle, [
                $key + 1,
                $value->major_area_name,
                $value->getMajorAreaCity->city_name,
                $value->getCreatedBy->name." - ".$value->getCreatedBy->login_id
            ]);
        }

        fclose($handle);

        return response()->download($fileName)->deleteFileAfterSend(true);

    }

    public function manage_minor_areas(Request $request){
        if($request->ajax()){
            $dt = new DataTables();
            
            $mnAreaQuery = MinorArea::with("getMajorArea", "getMajorArea.getMajorAreaCity","getCreatedBy")->newQuery();

            if($request->filled('minor_area_name')){
                $mnAreaQuery->where('minor_area_name', 'LIKE', '%' . $request->minor_area_name . '%');
            }

            if($request->filled("major_area_name")){
                $mnAreaQuery->whereHas('getMajorArea', function ($query) use ($request) {
                    $query->where('major_area_name', 'LIKE', '%' .$request->city_name. "%");
                });
            }

            if($request->filled("city_name")){
                $mnAreaQuery->whereHas('getMajorArea', function ($query) use ($request) {
                    $query->where('city_id', $request->city_name);
                });
            }

            return $dt->eloquent($mnAreaQuery)
                   ->addIndexColumn()
                   ->addColumn("actions", function($data){
                        return html()->a(route('admin.edit_minor_area', ['id' => Crypt::encrypt($data->minor_area_id)]))->class("btn-action edit edit-btn")->html("<i class='ri-pencil-line'></i>")->target("_blank");  
                    })
                    ->rawColumns(['actions'])
                   ->make(true);
        }

        return view('admin.city.minor_area.minor_areas', [
            'cities' => City::pluck("city_name", "city_id")->toArray()
        ]);
    }

    public function create_minor_area(Request $request){
        if($request->isMethod("POST")) {
            $request->validate([
                "minor_area_name" => "required|min:3",
                'major_area_name' => "required|numeric|exists:major_areas,major_area_id",
                'city_name' => "required|numeric|exists:cities,city_id"
            ]);

            $mnArea = MinorArea::create([
                'minor_area_name' => $request->minor_area_name,
                'major_area_id' => $request->major_area_name,
                'created_by' => Auth::user()->user_id
            ]);

            $mjArName = MajorArea::where(['major_area_id' => $request->major_area_id])->value("major_area_name");
 
            RecentActivity::create([
                'model_class' => MinorArea::class,
                'model_id' => $mnArea->minor_area_id,
                'activity_description' => Auth::user()->name." Have Created The Minor Area ".$mnArea->minor_area_name." Under The Major Area ".$mjArName
            ]);

            return response()->json(['status' => REQUEST_PROCESSED_SUCCESSFULLY, 'message' => "Minor Area Added Successfully."]);
        }

        return view('admin.city.minor_area._form', [
            'minor_area_data' => null,
            'formType' => "create",
            'cities' => City::pluck('city_name', "city_id")->toArray(),
            'action' => route('admin.store_minor_area')
        ]);
    }

    public function edit_minor_area(Request $request, $id){
        $decID = Crypt::decrypt($id);
        $mnArea = MinorArea::with("getMajorArea", "getMajorArea.getMajorAreaCity")->findOrFail($decID);
        
        if($request->isMethod("POST")) {
            $request->validate([
                "minor_area_name" => "required|min:3",
                'major_area_name' => "required|numeric|exists:major_areas,major_area_id",
                'city_name' => "required|numeric|exists:cities,city_id"
            ]);

            $mnArea->minor_area_name = $request->minor_area_name;
            $mnArea->major_area_name = $request->major_area_name;

            $mnArea->save();

            $mjArName = MajorArea::where(['major_area_id' => $request->major_area_id])->value("major_area_name");
 
            RecentActivity::create([
                'model_class' => MinorArea::class,
                'model_id' => $mnArea->minor_area_id,
                'activity_description' => Auth::user()->name." Have Updted The Minor Area  Details ".$mnArea->minor_area_name." Under The Major Area ".$mjArName
            ]);

            return response()->json(['status' => REQUEST_PROCESSED_SUCCESSFULLY, 'message' => "Minor Area Updated Successfully."]);
        }

        return view('admin.city.minor_area.edit', [
            'formType' => "edit",
            'minor_area_data' => $mnArea,
            'cities' => City::pluck("city_name", "city_id")->toArray(),
            'action' => route('admin.update_major_area', ['id' => Crypt::encrypt($mnArea->minor_area_id)])
        ])->render();
    }

    public function get_city_major_areas(Request $request){
        $cityID = City::where(['city_id' => $request->cityID])->first();

        if($cityID == null){
            return response()->json(['status' => 0, 'message' => "Invalid City Name"]);
        }

        $cityMajorAreas = MajorArea::where(['city_id' => $request->cityID])->select("major_area_name", "major_area_id")->get();

        return response()->json(['status' => REQUEST_PROCESSED_SUCCESSFULLY, 'data' => $cityMajorAreas]);
    }

    public function export_minor_areas(){
    
        ini_set('memory_limit', '16384M');
        ini_set('max_execution_time', '900');

        $mjAreas = MinorArea::with("getCreatedBy", "getMajorArea","getMajorArea.getMajorAreaCity")->get();
        $csvHeaders = ["S.No", "City", "Major Area", "Minor Area", "Created By"];

        $fileName = 'MinorAreasLists-' . now()->format('Y-m-d-H-i-s') . '.csv';
        $handle = fopen($fileName, "w");

        fputcsv($handle, $csvHeaders);

        foreach ($mjAreas as $key => $value) {
            fputcsv($handle, [
                $key + 1,
                $value->getMajorArea->getMajorAreaCity->city_name,
                $value->getMajorArea->major_area_name,
                $value->minor_area_name,
                $value->getCreatedBy->name." - ".$value->getCreatedBy->login_id
            ]);
        }

        fclose($handle);

        return response()->download($fileName)->deleteFileAfterSend(true);

    }

    public function shipping_method(Request $request){
        if($request->ajax()){
            $shpMethodQuery = ShippingMethod::query();

            if($request->filled("shipping_method_status")){
                $shpMethodQuery->where("status", $request->shipping_method_status);
            }

            if($request->filled("cost")){
                $shpMethodQuery->where("cost", $request->cost);
            }

            if($request->filled("shipping_method_name")){
                $shpMethodQuery->where('shipping_method_name', 'LIKE', '%' . $request->shipping_method_name . '%');
            }
            
            return DataTables::of($shpMethodQuery)
                ->addIndexColumn()
                ->addColumn('actions', function($data){
                    return html()->a(route('admin.edit_shipping_method', ['id' => $data->shipping_method_id]))->class("btn-action edit edit-btn")->html("<i class='ri-pencil-line'></i>");
                })
                ->rawColumns(["actions"])
                ->make(true);
        }

        return view('admin.shipping_method.index');
    }

    public function create_shipping_method(Request $request){
        if($request->isMethod("POST")){
            $request->validate([
                'cost' => "required|min_digits:2|numeric",
                'shipping_method_name' => "required|min:3|unique:shipping_methods,shipping_method_name",
                'status' => "required|numeric|in:1,0",
                "shipping_method_description" => "required"
            ]);

            $nShipMethod = ShippingMethod::create([
                'shipping_method_name' => $request->shipping_method_name,
                'status' => $request->status,
                'cost' => $request->cost,
                'description' => $request->shipping_method_description
            ]);

            RecentActivity::create([
                'activity_description' => Auth::user()->name." Have Created New Shipping Method $nShipMethod->shipping_method_name",
                'model_id' => $nShipMethod->shipping_method_id,
                'model_class' => ShippingMethod::class
            ]);

            return redirect()->route('admin.shipping_method')->with('success', "New Shipping Method Created Successfully.");
        } else {
            return view('admin.shipping_method.create');
        }
    }

    public function edit_shipping_method(Request $request, $id){
        $shippingMethodData = ShippingMethod::findOrFail($id);

        if($request->isMethod("POST")){
            $request->validate([
                'shipping_method_name' => "required|min:3|unique:shipping_methods,shipping_method_name,$id,shipping_method_id",
                'cost' => "required|min_digits:2|numeric",
                'status' => "required|numeric|in:1,0",
                "shipping_method_description" => "required"
            ]);

            $shippingMethodData->where("shipping_method_id", $id)->update([
                'shipping_method_name' => $request->shipping_method_name,
                'status' => $request->status,
                'cost' => $request->cost,
                'description' => $request->shipping_method_description
            ]);

            RecentActivity::create([
                'activity_description' => Auth::user()->name." Have Updated The Shipping Method $shippingMethodData->shipping_method_name Detail(s)",
                'model_id' => $shippingMethodData->shipping_method_id,
                'model_class' => ShippingMethod::class
            ]);

            return redirect()->route('admin.shipping_method')->with('success', "Shipping Method Details Updated Successfully.");
        } else {
            return view('admin.shipping_method.edit', [
                'shippingMethodData' => $shippingMethodData
            ]);
        }
    }

    public function manage_orders(Request $request){

        $orders = Order::with("getOrderBy")->with("getOrderLineItems", "getOrderLineItems.getLineItemProduct")->orderByDesc("order_id")->newQuery();
        
        if($request->filled("search")){
            $orders->orWhere('customer_order_id', 'LIKE', '%' . $request->search . '%');
        }

        if($request->filled("status")){
            $orders->orWhere("order_status", $request->status);
        }

        $orders = $orders->paginate(20);
        return view('admin.orders.manage_orders', compact("orders"));
    }

    public function order_detail($orderID){
        $orderDetail = Order::with("getOrderBy", "getShippingMethod", "getOrderTimeLine", "getOrderTimeLine.getProcessBy")->where("customer_order_id", $orderID)->firstOrFail();
        
        $content =  view('admin.orders.view_order_detals', [
            'orderData' => $orderDetail
        ])->render();
        $orderID = $orderDetail->customer_order_id;
        
        return response()->json([
            'view' => $content,
            'orderID' => $orderID,
            'orderStatus' => $orderDetail->order_status
        ]);
    }

    public function update_order_status(Request $request){
        $orderData = Order::where("customer_order_id", $request->order_id)->first();

        if(empty($orderData)){
            return response()->json([
                'status' => REQUEST_PROCESSED_SUCCESSFULLY,
                'message' => "Invalid Order ID"
            ]);
        }

        $validOrderStatus = [
            ORDER_STATUS_CANCELLED,
            ORDER_STATUS_PENDING,
            ORDER_STATUS_PROCESSED,
            ORDER_STATUS_PROCESSING,
            ORDER_STATUS_SHIPPER
        ];

        if(!in_array($request->order_status, $validOrderStatus)){
            return response()->json([
                'status' => REQUEST_PROCESSED_SUCCESSFULLY,
                'message' => "Invalid Order Status"
            ]);
        }

        $orderData->update([
            'order_status' => $request->order_status,
            'order_process_by' => Auth::user()->user_id
        ]);

        OrderHistory::create([
            'order_id' => $orderData->order_id,
            'status' => $request->order_status,
            'process_by' => Auth::user()->user_id
        ]);

        return response()->json([
            'status' => REQUEST_PROCESSED_SUCCESSFULLY,
            'message' => "Order Status Updated Successfully"
        ]);
    }

    public function logout(){
        Auth::logout();
        return redirect()->route("admin.sign-in")->with('success', "User Logged Out");
    }
}
