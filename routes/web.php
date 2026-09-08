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