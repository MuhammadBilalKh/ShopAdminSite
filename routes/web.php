<?php

use App\Http\Controllers\AdminController;
use App\Http\Middleware\AuthUser;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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

                Route::post("/submit-major-area", [AdminController::class, 'create_major_area'])->name('admin.submit_major_area');
                Route::post("/major-area/{id}/update", [AdminController::class, 'edit_major_area'])->name('admin.update_city');
                Route::post("/store", [AdminController::class, 'create_city'])->name('admin.store_city');
            });
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

Route::fallback(function(){
    return 'Not Found';
});