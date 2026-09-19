<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShopController;
use App\Http\Middleware\AuthUser;
use App\Http\Middleware\CustomerAuth;
use Illuminate\Support\Facades\Route;

Route::get("/", [ShopController::class, 'index'])->name("shopping.index");
Route::get("/product-detail", [ShopController::class, 'product_detail'])->name("shopping.product_detail");
Route::get("/show-all-products", [ShopController::class, 'showAllProducts'])->name("shopping.show_all_products");
Route::get("/products", [ShopController::class, 'products_lists'])->name('shopping.products_lists');

Route::prefix("site-administrator")->group(function(){

    Route::get("/sign-in", [AdminController::class, 'admin_sign_in'])->name("admin.sign-in");
    Route::post("/sign-in", [AdminController::class, 'admin_sign_in'])->name("admin.submit_sign_in");

    Route::middleware([AuthUser::class])->group(function(){
        Route::get("/", [AdminController::class, 'show_admin_dashboard'])->name("admin.show_admin_dashboard");
        Route::get("/recent-activities", [AdminController::class, 'view_recent_activities'])->name("admin.view_recent_activities");
        
        Route::prefix("region-management")->group(function(){
            Route::prefix("city")->group(function(){
                Route::get("/", [AdminController::class, 'manage_cities'])->name("admin.cities");
                Route::get("/create", [AdminController::class, 'create_city'])->name("admin.create_city");
                Route::get("/{id}/edit", [AdminController::class, 'edit_city'])->name('admin.edit_city');
                Route::get("/export", [AdminController::class, 'export_cities'])->name('admin.export_cities');
                
                Route::get("/major-areas", [AdminController::class, 'manage_major_areas'])->name("admin.city_major_areas");
                Route::get("/major-area/create", [AdminController::class, 'create_major_area'])->name('admin.create_major_area');
                Route::get("/major-area/{id}/edit", [AdminController::class, 'edit_major_area'])->name('admin.edit_major_area');
                Route::get("/major-area/{id}/update", [AdminController::class, 'edit_major_area'])->name('admin.update_major_area');
                Route::get("/major-area/export", [AdminController::class, 'export_major_areas'])->name('admin.export_major_areas');

                Route::get("/minor-areas", [AdminController::class, 'manage_minor_areas'])->name('admin.manage_minor_areas');
                Route::get("/create-minor-areas", [AdminController::class, 'create_minor_area'])->name('admin.create_minor_area');
                Route::get("/{id}/edit-minor-area", [AdminController::class, 'edit_minor_area'])->name('admin.edit_minor_area');
                Route::get("/minor-areas/export", [AdminController::class, 'export_minor_areas'])->name("admin.export_minor_areas");
                
                Route::post("/store", [AdminController::class, 'create_city'])->name('admin.store_city');
                Route::post("/submit-major-area", [AdminController::class, 'create_major_area'])->name('admin.submit_major_area');
                Route::post("/major-area/{id}/update", [AdminController::class, 'edit_major_area'])->name('admin.update_city');
                Route::post("/store-minor-area", [AdminController::class, 'create_minor_area'])->name('admin.store_minor_area');
                Route::post("/{id}/update-minor-area", [AdminController::class, 'edit_minor_area'])->name("admin.update_minor_area");
                Route::post("/get-city-major_areas", [AdminController::class, 'get_city_major_areas'])->name('admin.get_city_major_area');
            });
        });

        Route::prefix("orders")->group(function(){
            Route::get("/list", [AdminController::class, 'manage_orders'])->name("admin.manage_orders");
            Route::get("/detail/{orderID}", [AdminController::class, 'order_detail'])->name("admin.order_detail");
            Route::post("/update-status", [AdminController::class, 'update_order_status'])->name("admin.update_order_status");
        });

        Route::prefix("shipping-method")->group(function(){
            Route::get("/", [AdminController::class, 'shipping_method'])->name("admin.shipping_method");
            Route::get("/create", [AdminController::class, 'create_shipping_method'])->name("admin.create_shipping_method");
            Route::get("/{id}/edit", [AdminController::class, 'edit_shipping_method'])->name("admin.edit_shipping_method");

            Route::post("/store", [AdminController::class, 'create_shipping_method'])->name("admin.submit_shipping_method");
            Route::post("/{id}/update", [AdminController::class, "edit_shipping_method"])->name("admin.update_shipping_method");
        });

        Route::prefix("product")->group(function(){
            Route::get("/products-list", [AdminController::class, "product_lists"])->name("admin.show_product_lists");
            Route::get("/product/create", [AdminController::class, 'create_product'])->name('admin.create_product');
            Route::get("/{id}/edit", [AdminController::class, "edit_product"])->name("admin.edit_product_detail");
            Route::get("/export-products", [AdminController::class, 'export_all_products'])->name("admin.export_all_products");
            Route::post("/{id}/update", [AdminController::class, 'edit_product'])->name("admin.update_product_detail");
            Route::post("/product/store", [AdminController::class, 'create_product'])->name('admin.submit_create_product');
        });
        
        Route::prefix("product-category")->group(function(){
            Route::get("/", [AdminController::class, 'product_category_lists'])->name('admin.product_category_lists');
            Route::get("/create-category", [AdminController::class, 'create_category'])->name("admin.create_category");
            Route::get("/{id}/edit", [AdminController::class, 'edit_product_category'])->name("admin.edit_product_category");
            Route::get("/{id}/delete", [AdminController::class, 'delete_product_category'])->name("admin.delete_product_category");
            Route::get("/export-product-category", [AdminController::class, 'export_product_category'])->name('admin.export_product_category');

            Route::post("/post-create-category", [AdminController::class, 'create_category'])->name("admin.submit_create_category");
            Route::post("/{id}/update", [AdminController::class, 'edit_product_category'])->name('admin.update_product_category');
            Route::post("/{id}/remove", [AdminController::class, 'delete_product_category'])->name("admin.remove_product_category");
        });
          
        Route::get("/log-out", [AdminController::class, 'logout'])->name('admin.logout');
    });
            
});

Route::prefix("customers")->group(function(){
    Route::get("/sign-in", [ShopController::class,'customer_sign_in'])->name("shopping.customer_sign_in");
    Route::get("/sign-up", [ShopController::class, 'sign_up'])->name("shopping.sign_up");

    Route::post("/store-new-customer", [ShopController::class, 'sign_up'])->name("shopping.store_new_customer");
    Route::post("/authenticate/customer", [ShopController::class, 'customer_sign_in'])->name("shopping.authenticate_customer");

    Route::get("/cart", [ShopController::class, 'view_cart'])->name("shopping.view_cart");

    Route::middleware([CustomerAuth::class])->group(function(){
        Route::get("/wishlist-cart-count", [ShopController::class, 'get_wishlist_and_cart_count'])->name("shopping.get_wishlist_and_cart_count");
        Route::get("/checkout", [ShopController::class, 'process_checkout'])->name("shopping.checkout");
        Route::get("/Orders", [ShopController::class, 'customer_orders'])->name("shopping.customer_orders");

        Route::post("/checkout/submit", [ShopController::class, 'process_checkout'])->name("shopping.submit_checkout");
        Route::post("/add-to-cart/{productID}", [ShopController::class, 'add_to_cart'])->name("customer.add_to_cart");
        Route::post("/cart/manage", [ShopController::class, 'manage_customer_cart'])->name('customer.manage_customer_cart');
    });
});