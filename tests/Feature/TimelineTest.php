<?php

namespace Tests\Feature;

use App\Country;
use App\Report;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimelineTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_returns_series_for_a_country()
    {
        Carbon::setTestNow();

        $country = factory(Country::class)->create();

        $report = factory(Report::class)->create([
            'date' => Carbon::now(),
            'country_id' => $country->id,
            'confirmed' => 1,
            'recovered' => 2,
            'deaths' => 0,
        ]);

        $parameters = base64_encode(json_encode([$country->id]));

        $this->get("/timeline?countries={$parameters}")->assertViewHas('page.props.series', [
            'provinces' => [],
            'countries' => [
                [
                    'date' => Carbon::now(),
                    'id' => $country->id,
                    'confirmed' => 1,
                    'deaths' => 0,
                    'recovered' => 2,
                ],
            ],
        ]);
    }
}
