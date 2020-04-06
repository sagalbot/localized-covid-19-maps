<?php

namespace App\Providers;

use App\Country;
use App\Http\Requests\SelectedRegionsRequest;
use App\Http\Resources\SelectedRegionResource;
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

        Inertia::share('selectedRegions', function () {
            $selected = collect(request()->query('regions', []))
                ->groupBy('type')
                ->map(function (Collection $ids, $model) {
                    return $model::whereIn('id', $ids->pluck('id')->toArray())->get();
                })
                ->flatten();

            return SelectedRegionResource::collection($selected);
        });
    }
}
