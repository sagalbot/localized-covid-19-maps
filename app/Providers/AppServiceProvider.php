<?php

namespace App\Providers;

use App\Country;
use App\Http\Requests\SelectedRegionsRequest;
use App\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
            $selected = collect(request()->query('regions', []))
                ->groupBy('type')
                ->map(function (Collection $ids, $model) {
                    return $model::whereIn('id', $ids->pluck('id')->toArray())->get();
                })
                ->flatten();

            return RegionResource::collection($selected);
        });
    }
}
