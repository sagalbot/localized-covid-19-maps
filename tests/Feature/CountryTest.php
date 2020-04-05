<?php

namespace Tests\Feature;

use App\Country;
use App\Province;
use App\Report;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_has_provinces()
    {
        $country = factory(Country::class)->create();
        $provinces = factory(Province::class, 2)->create([
            'country_id' => $country->id,
        ]);

        $this->assertEquals(2, $country->provinces->count());
        $this->assertEquals($provinces->pluck('id')->toArray(), $country->provinces->pluck('id')->toArray());
    }

    /**
     * @test
     */
    public function it_has_reports()
    {
        $country = factory(Country::class)->create();
        $reports = factory(Report::class, 2)->create([
            'country_id' => $country->id,
            'province_id' => null,
        ]);

        $this->assertEquals(2, $country->reports()->count());
        $this->assertEquals($reports->pluck('id')->toArray(), $country->reports->pluck('id')->toArray());
    }

    /**
     * @test
     */
    public function it_can_get_the_latest_report()
    {
        Carbon::setTestNow(Carbon::now());

        $country = factory(Country::class)->create();

        $latest = factory(Report::class)->create([
            'country_id' => $country->id,
            'province_id' => null,
            'date' => Carbon::now(),
        ]);

        $older = factory(Report::class)->create([
            'country_id' => $country->id,
            'province_id' => null,
            'date' => Carbon::now()->subDay(),
        ]);

        $this->assertEquals($country->latestReport->toArray(), $latest->toArray());
    }

    /**
     * @test
     */
    public function it_can_generate_a_time_series()
    {
        Carbon::setTestNow(Carbon::now()->micro(0));

        /** @var \App\Country $country */
        $country = factory(Country::class)->create();
        $province = factory(Province::class)->create([
            'country_id' => $country->id,
        ]);

        factory(Report::class, 2)->create([
            'country_id' => $province->country->id,
            'province_id' => $province->id,
            'confirmed' => 1,
            'deaths' => 5,
            'recovered' => 0,
            'date' => Carbon::now(),
        ]);

        factory(Report::class, 2)->create([
            'country_id' => $province->country->id,
            'province_id' => $province->id,
            'confirmed' => 3,
            'deaths' => 0,
            'recovered' => 0,
            'date' => Carbon::now()->subDay(),
        ]);

        $this->assertEquals(
            [2, 6],
            $country
                ->timeSeries()
                ->pluck('confirmed')
                ->toArray(),
        );
        $this->assertEquals(
            [10, 0],
            $country
                ->timeSeries()
                ->pluck('deaths')
                ->toArray(),
        );
        $this->assertEquals(
            [0, 0],
            $country
                ->timeSeries()
                ->pluck('recovered')
                ->toArray(),
        );
    }
}
