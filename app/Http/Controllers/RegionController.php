<?php

namespace App\Http\Controllers;

use App\Country;
use App\Province;
use App\Report;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\RegionResource;

class RegionController extends Controller
{
    public function __invoke(Request $request)
    {
        return Inertia::render('Regions', [
            'regions' => RegionResource::collection($this->regions()),
        ]);
    }

    public function regions()
    {
        $provinces = $this->provinces();

        return $this->countries()
            ->map(function (Country $country) use ($provinces) {
                if ($country->provinces->isNotEmpty()) {
                    $country->latestReport = $provinces
                        ->where('country_id', $country->id)
                        ->reduce(function (Report $report, Province $province) {
                            $report->country_id = $province->country_id;
                            if ($province->latestReport) {
                                $report->confirmed += $province->latestReport->confirmed;
                                $report->recovered += $province->latestReport->recovered;
                                $report->deaths += $province->latestReport->deaths;
                            } else {
                                Log::critical(
                                    "Province ID {$province->id} has no latest report. The name may have changed.",
                                );
                            }
                            return $report;
                        }, new Report(['country_id' => $country->id]));
                }
                return $country;
            })
            ->concat($provinces)
            ->sortBy('name');
    }

    public function countries(): \Illuminate\Database\Eloquent\Collection
    {
        return Country::with(['provinces'])->get();
    }

    public function provinces(): \Illuminate\Database\Eloquent\Collection
    {
        return Province::with('latestReport')->get();
    }
}
