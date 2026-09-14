<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Models\WishList;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index(){
        $featureProducts = Product::with("getProductCategory")->where(['is_featured' => FEATURED_PRODUCT])->get();
        $allProds = Product::orderByDesc("product_id")->paginate(10);
        $customers = Customer::with("getOrders")->count();

        return view('shop.index', [
            'featuredProducts' => $featureProducts,
            'allProds' => $allProds,
            'totalProds' => Product::count(),
            'avgRating' => ProductReview::avg("rating"),
            'hpCustomers' => $customers
        ]);
    }

    public function product_detail(Request $request){
        $prodID = $request->input('_id');
        $productData = Product::with("getProductCategory", "getProductImages", "tags", "tags.getTags")->where("unique_product_id", $prodID)->first();
        
        if(!empty($productData)){
            $relatedProds = Product::with("getProductCategory")->where(['category_id' => $productData->category_id])->whereNot("product_id", $productData->product_id)->orderByDesc("product_id")->limit(5)->get();
        } else {
            $relatedProds = [];
        }
            
        return view('shop.product_detail', compact("productData", "relatedProds"));
    }

    public function showAllProducts(Request $request){
        $products = Product::with("getProductCategory",'tags.getTags')->orderByDesc("product_id")->paginate(20);
        $view = view("shop.all_products", [
            'productsArr' => $products,
            'sectionID' => "AllProds",
            'productSectionName' => "All Products"
        ])->render();

        return response()->json([
            'status' => REQUEST_PROCESSED_SUCCESSFULLY,
            'products_view' => $view
        ]);
    }

    public function products_lists(Request $request){
        $allProductsTags = Tag::withCount("getProducts")->distinct()->get();
        $categories = Category::withCount("products")->distinct()->get();
        return view('shop.products', [
            'tags' => $allProductsTags,
            'category' => $categories
        ]);
    }

    public function add_to_cart(Request $request, $productID){
        $product = Product::where("unique_product_id", $productID)->firstOrFail();
        $cart = new Cart();

        $productPrice = 0;

        if($product->quantity == 0){
            return response()->json([
                'status' => 0,
                'message' => $product->name . " Got Out of Stock. Please Try Again Later"
            ]);
        }

        $productPrice = $product->sales_price > 0
            ? $product->sales_price
            : $product->regular_price;

        $cartItem = $cart->firstOrCreate(
            [
                'product_id' => $product->product_id,
                'customer_id' => Auth::guard("customer")->user()->customer_id,
            ],
            [
                'quantity' => 1,
                'price' => $productPrice,
            ]);

        if($cartItem){
            $totalCartItems = Cart::where("customer_id", Auth::guard('customer')->user()->customer_id)->count();
            return response()->json([
                'status' => REQUEST_PROCESSED_SUCCESSFULLY,
                'productName' => $product->product_name,
                'totalCartItems' => $totalCartItems
            ]);
        }
    }

    public function customer_sign_in(Request $request){
        if($request->isMethod("POST")){
            $request->validate([
                'email' => "required|email",
                'password' => "required"
            ]);
    
            $customer = Auth::guard('customer')->attempt(['email_address'=> $request->email,'password'=> $request->password]);
    
            if($customer){
                return redirect()->route("shopping.index");
            } else {
                return redirect()->back()->withErrors(['email'=> 'Invalid Credentials'])->withInput();
            }
        } else {
            return view('shop.authentication.sign-up');
        }
    }

    public function sign_up(Request $request){
        if($request->isMethod('POST')){
            $request->validate([
                'email' => "required|email|unique:customers,email_address",
                'password' => "required|min:8",
                'confirm_password' => "required|same:password",
                'customer_address' => "required",
                'mobile_number' => "required|unique:customers,mobile_number",
                'f_name' => "required",
                'l_name' => "required",
                "cnic_number" => "required|max:13|unique:customers,cnic"
            ]);

            Customer::create([
                'email_address' => $request->email,
                'password' => $request->password,
                'address' => $request->customer_address,
                'full_name' => $request->f_name . " " . $request->l_name,
                'cnic' => $request->cnic_number,
                'mobile_number' => $request->mobile_number,
                'account_status' => 1,
            ]);

            $customer = Auth::guard("customer")->attempt([
                'email' => $request->email,
                'password' => $request->password
            ]);

            if($customer){
                return redirect()->route('shopping.index');
            }
        } else {
            return view('shop.authentication.new_customer');
        }
    }

    public function get_wishlist_and_cart_count(){
        return response()->json([
            'cartCount' => Cart::where("customer_id", Auth::guard("customer")->user()->customer_id)->count(),
            'wishlistCount' => WishList::where(['customer_id' => Auth::guard("customer")->user()->customer_id])->count()
        ]);
    }

    public function logoutCustomer(){
        Auth::guard("customer")->logout();
        return redirect()->route("shopping.index");
    }
}
