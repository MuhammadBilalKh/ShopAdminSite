<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Tag;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductHasTag;
use App\Models\RecentActivity;
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

        $recentActivties = RecentActivity::orderByDesc("created_at")->limit(10)->get();
        return view('admin.dashboard', compact('recentOrders','totalCategories', 'totalOrders', 'totalProducts', 'recentActivties'));
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
                    $products->where(['quantity'=> LOW_STOCK_QUANTITY]);
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
                    if($data->quantity <= LOW_STOCK_QUANTITY && $data->quantity > 0){
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
                    $uniqueImageName = $imageFile->hashName();
                    $imageFile->move(public_path('product_profile_image'), $uniqueImageName);
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

                $productProfileImage = $productData->product_profile_image;
                
                if($request->hasFile("product_image")){
                    $imageFile = $request->file("product_image");
                    $uniqueImageName = $imageFile->hashName();
                    $imageFile->move(public_path('product_profile_image'), $uniqueImageName);
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
                $productData->product_profile_image = $productProfileImage;
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
        
        exit(0);
    }

    public function logout(){
        Auth::logout();
        return redirect()->route("admin.sign-in")->with('success', "User Logged Out");
    }
}
