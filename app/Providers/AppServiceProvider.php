<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share(
             'pending_orders',
             Order::where('order_status', 0)->count()
         );

         View::composer('layout.shop.footer', function($view){
            $categoryNames = Category::select("category_name")->get();
            return $view->with("category_names", $categoryNames);
         });
    }
}
