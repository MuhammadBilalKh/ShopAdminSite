<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductHasTag;
use Exception;
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

        return view('admin.dashboard', compact('recentOrders','totalCategories', 'totalOrders', 'totalProducts'));
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
                $products->where(["category_id" => $request->category_id]);
            }

            if($request->filled("price")){
                $products->where(['regular_price' => $request->price]);
            }

            if($request->filled("quantity")){
                $products->where(['quantity' => $request->quantity]);
            }

            if($request->filled("stock_status")){
                if($request->stock_status == 0){
                    $products->where(['quantity' => 0]);
                } else if($request->stock_status == LOW_STOCK_QUANTITY){
                    $products->where(['quantity'=> LOW_STOCK_QUANTITY]);
                } else {
                    $products->where(['quantity', ">", LOW_STOCK_QUANTITY]);
                }
            }

            $dataTables = new DataTables();
            return $dataTables->eloquent($products)
                ->addIndexColumn()
                ->addColumn("actions", function($data){
                    return $data->product_id;
                })
                ->addColumn("stock_status", function($data){
                    if($data->quantity <= LOW_STOCK_QUANTITY && $data->quantity > 0){
                        return "Low Stock";
                    } else if($data->quantity == 0){
                        return "Out of Stock";
                    } else if($data->quantity > LOW_STOCK_QUANTITY){
                        return "In Stock";
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

                $category->delete();
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
             'category_id' => 'required|numeric|exists:categories,category_id',
             'product_price' => "required|numeric|min:1",
             'sale_price' => "nullable|numeric|min:1",
             'stock_quantity' => "required|numeric|min:1",
             'product_image' => "required|image",
             'product_description' => "required",
             'product_tags' => "required"
          ]);

          
          try {
              DB::beginTransaction();

              
          $product = new Product();
              
          $product->product_name = $request->input("name");
          $product->product_description = $request->input("description");
          $product->category_id = $request->input("category_id");
          $product->unique_product_id = Str::slug($request->product_name)."-".Str::uuid();
          $product->regular_price = $request->product_price;
          $product->quantity = $request->stock_quantity;
          $product->sales_price = $request->sale_price;

          $product->save();
          
          $tagsArr = explode(",", $request->product_tags);
          
            foreach( $tagsArr as $tag ){
                $tags = Tag::create([
                    'tag_name' => $tag
                ]);

                ProductHasTag::create([
                    'product_id' => $product->product_id,
                    'tag_id' => $tags->tag_id
                ]);
            }
                    
            DB::commit();
            return response()->json(['status' => REQUEST_PROCESSED_SUCCESSFULLY]);

            } catch(Exception $e){
                DB::rollBack();
                return response()->json(['status' => 0]);
            }

       }

       $categories = Category::where(['status' => STATUS_ACTIVE])->pluck("category_name", "category_id")->toArray();
       return view('admin.products.create', compact('categories'))->render();
    }

    public function logout(){
        Auth::logout();
        return redirect()->route("admin.sign-in")->with('success', "User Logged Out");
    }
}
