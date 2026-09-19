<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\WishList;
use App\Models\Category;
use App\Models\Customer;
use App\Models\OrderHistory;
use Illuminate\Http\Request;
use App\Models\OrderLineItem;
use App\Models\ProductReview;
use App\Models\ShippingMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        $existsCartItem = Cart::where(['customer_id' => Auth::guard("customer")->user()->customer_id, 'product_id' => $product->product_id])->first();

        if(!empty($existsCartItem)){
            $cartItem = Cart::where([
                'customer_id' => Auth::guard('customer')->user()->customer_id,
                'product_id' => $product->product_id
            ])->update([
                'quantity' => $existsCartItem->quantity + 1,
                'price' => $productPrice
            ]);
        } else {
            $cartItem = Cart::create([
                'customer_id' => Auth::guard('customer')->user()->customer_id,
                'product_id' => $product->product_id,
                'quantity' => 1,
                'price' => $productPrice
            ]);
        }

        if($cartItem){
            $totalCartItems = Cart::where("customer_id", Auth::guard('customer')->user()->customer_id)->count();
            return response()->json([
                'status' => REQUEST_PROCESSED_SUCCESSFULLY,
                'productName' => $product->product_name,
                'totalCartItems' => $totalCartItems
            ]);
        }
    }

    public function manage_customer_cart(Request $request){
        $reqType = $request->request_type;
        $customerID = Auth::guard('customer')->user()->customer_id;
        $reqProductID = $request->product_id;
        $msg = "";

        $productID = Product::where(['unique_product_id' => $reqProductID])->value("product_id");
        if($reqType == 1){
            Cart::removeCart($customerID);
            $msg = "Cart Have Been Made Empty Successfully.";
        } else if($reqType == 2){
            Cart::removeCartItem($customerID, $productID);
            $msg = "Cart Item Removed Successfully.";
        } else if($reqType == 3){
            Cart::updateCartItem($customerID, $productID, $request->product_quantity);
            $msg = "Cart Updated Successfully.";
        }

        $cartItem = Cart::where(['customer_id' => $customerID])->get();
        $ordSummary = view('shop.order_summary', [
            'data' => $cartItem,
            'cartItems' => $cartItem,
        ])->render();

        return response()->json([
            'status' => REQUEST_PROCESSED_SUCCESSFULLY,
            'message' => $msg,
            'orderSummary' => $ordSummary
        ]);
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

    public function view_cart(){
        $cartItems = Cart::with("getCartProduct")->where(['customer_id' => Auth::guard('customer')->user()->customer_id])->get();

        return view('shop.cart', compact('cartItems'));
    }

    public function process_checkout(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $customerId = $customer->customer_id;

        if (!$request->isMethod('POST')) {

            $placeItems = Cart::with('getCartProduct')
                ->where('customer_id', $customerId)
                ->get();

            $shippingMethods = ShippingMethod::where("status", STATUS_ACTIVE)->get();
            return view('shop.order_checkout', compact('placeItems', "shippingMethods"));
        }

        $request->validate([
            'shipping_address' => 'required',
            'zip_code' => 'required|numeric',
            'shipping' => "required|numeric|exists:shipping_methods,shipping_method_id"
        ], [
            'shipping_address.required' => 'Shipping Address Is Required',
            'zip_code.required' => 'City Zip Code Is Required',
            'zip_code.numeric' => "Invalid Zip Code Format",
            "shipping.required" => "Please Select The Shipping Method"
        ]);

        $placeItems = Cart::with('getCartProduct')
            ->where('customer_id', $customerId)
            ->get();

        if ($placeItems->isEmpty()) {

            return redirect()->back()
                ->with('error', 'Your cart is empty.');
        }

        DB::beginTransaction();

        try {

            $zeroQuantity = [];
            $totalAmount = 0;

            foreach ($placeItems as $cartItem) {

                $product = $cartItem->getCartProduct;

                if (!$product) {

                    $zeroQuantity[$cartItem->product_id] = 'Product is no longer available';

                    continue;
                }

                if ($product->quantity <= 0) {

                    $zeroQuantity[$product->unique_product_id] = $product->product_name;

                    continue;
                }

                $totalAmount += $cartItem->quantity * $cartItem->price;
            }

            if (!empty($zeroQuantity)) {

                DB::rollBack();

                return redirect()->back()
                    ->with('error', 'Following Products Are Out Of Stock Now.')
                    ->with('zeroQuantity', $zeroQuantity);
            }

            $nOrder = Order::create([
                'customer_order_id' => Str::uuid7(),
                'total_amount' => ($totalAmount + 100),
                'customer_id' => $customerId,
                'order_status' => ORDER_STATUS_PENDING,
                'notes' => $request->optional_notes,
                'shipping_method_id' => $request->shipping
            ]);

            foreach ($placeItems as $cartItem) {

                OrderLineItem::create([
                    'order_id' => $nOrder->order_id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price
                ]);
            }

            Cart::where('customer_id', $customerId)->delete();

            OrderHistory::create([
                'order_id' => $nOrder->order_id,
                'customer_id' => Auth::guard('customer')->user()->customer_id,
                'status' => ORDER_STATUS_PENDING,
                'process_by' => SYSTEM_ID
            ]);

            DB::commit();

            return redirect()
                ->route('shopping.index')->with('success',"Your Order Has Been Placed Successfully. Your Order ID Is: <b>"    . $nOrder->customer_order_id    . "</b>");

        } catch (\Throwable $e) {

            DB::rollBack();
            report($e);
            return redirect()->back()->with('error','An Error Occurred While Processing Your Request.');
        }
    }

    public function customer_orders(){
        $orders = Order::with('getOrderLineItems', "getOrderLineItems.getLineItemProduct")->where("customer_id", Auth::guard("customer")->user()->customer_id)->orderByDesc("order_id")->paginate(10);

        return view('shop.place_orders', compact("orders"));
    }

    public function logoutCustomer(){
        Auth::guard("customer")->logout();
        return redirect()->route("shopping.index");
    }
}
