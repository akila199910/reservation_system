<?php

namespace App\Providers;

use App\Models\Business;
use App\Models\BusinessUsers;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }

        view()->composer(
            'layouts.business',
            function ($view) {

                $user = auth()->user();
                if($user->hasRole('super_admin')||$user->hasRole('admin')){
                    $business = Business::all();
                }else{
                    $business_ids = BusinessUsers::Where('user_id',$user->id)->pluck('business_id');
                    $business= Business::WhereIn('id',$business_ids)->get();
                }

                $view->with([
                    'business' => $business
                ]);
            }
        );
    }
}
