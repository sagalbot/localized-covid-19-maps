<?php

namespace App\Http\Controllers;

use App\Country;
use App\Http\Resources\TimelineResource;
use App\Province;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use App\Http\Resources\CountryResource;
use App\Http\Resources\ProvinceResource;
use App\Http\Requests\SelectedRegionsRequest;

class TimelineController extends Controller
{
    /**
     * @var \App\Http\Requests\SelectedRegionsRequest
     */
    private $request;

    /**
     * TimelineController constructor.
     *
     * @param \App\Http\Requests\SelectedRegionsRequest $request
     */
    public function __construct(SelectedRegionsRequest $request)
    {
        $this->request = $request;
    }

    public function __invoke()
    {
        return Inertia::render('Timeline', [
            'series' => TimelineResource::collection($this->series()),
        ]);
    }

    public function series(): Collection
    {
        return $this->query(Country::class, 'countries')->concat($this->query(Province::class, 'provinces'));
    }

    public function query(string $model, string $param): Collection
    {
        return $model
            ::with('reports')
            ->select('id', 'name')
            ->whereIn('id', $this->request->input($param, []))
            ->get();
    }
}
