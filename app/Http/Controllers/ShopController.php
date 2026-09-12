<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\ProductReview;


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
}
