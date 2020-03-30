<?php

namespace App\Providers;

use App\Country;
use App\Http\Resources\ProvinceResource;
use App\Province;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CountryResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Inertia::version(function () {
            return md5_file(public_path('mix-manifest.json'));
        });

        Inertia::share('countries', function () {
            $countries = Country::with('latestReport')->get();
            return CountryResource::collection($countries);
        });

        Inertia::share('provinces', function () {
            $provinces = Province::with('latestReport')->get();
            return ProvinceResource::collection($provinces);
        });
    }
}
