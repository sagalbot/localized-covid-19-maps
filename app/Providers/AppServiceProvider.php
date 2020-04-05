<?php

namespace App\Providers;

use App\Country;
use App\Province;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Resources\RegionResource;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();

        Inertia::version(function () {
            return md5_file(public_path('mix-manifest.json'));
        });

        Inertia::share('countries', function () {
            $countries = Country::with('latestReport')->get();
            return RegionResource::collection($countries);
        });

        Inertia::share('provinces', function () {
            $provinces = Province::with('latestReport')->get();
            return RegionResource::collection($provinces);
        });

        Inertia::share('selectedRegions', function () {
            $provinces = Province::whereIn('id', request()->input('provinces', []))->get();
            $countries = Country::whereIn('id', request()->input('countries', []))->get();

            return RegionResource::collection($provinces->concat($countries));
        });
    }
}
