<?php

namespace App\Http\Controllers;

use App\Http\Requests\SelectedRegionsRequest;
use App\Http\Resources\TimelineResource;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Illuminate\Http\Request;

class SuppressionController extends Controller
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
        return Inertia::render('Suppression', [
            'series' => TimelineResource::collection($this->series()),
        ]);
    }

    public function series(): Collection
    {
        return collect($this->request->query('regions', []))
            ->groupBy('type')
            ->map(function (Collection $ids, $model) {
                return $this->query($model, $ids->pluck('id')->toArray());
            })
            ->flatten();
    }

    public function query(string $model, array $ids): Collection
    {
        return $model
            ::with([
                'reports' => function (HasMany $query) {
                    $query->where('confirmed', '>=', 100);
                },
            ])
            ->whereIn('id', $ids)
            ->get();
    }
}
