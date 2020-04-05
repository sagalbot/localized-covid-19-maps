<?php

namespace Tests\Feature;

use App\Country;
use App\Province;
use App\Report;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProvinceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_belongs_to_a_country()
    {
        $province = factory(Province::class)->create();

        $this->assertEquals(1, $province->country()->count());
    }

    /**
     * @test
     */
    public function it_has_many_reports()
    {
        $province = factory(Province::class)->create();

        $reports = factory(Report::class, 2)->create([
            'province_id' => $province->id,
            'country_id' => $province->country->id,
        ]);

        $this->assertEquals(2, $province->reports()->count());
    }

    /**
     * @test
     */
    public function it_has_a_latest_report()
    {
        Carbon::setTestNow(Carbon::now());

        $province = factory(Province::class)->create();

        $latest = factory(Report::class)->create([
            'province_id' => $province->id,
            'date' => Carbon::now(),
        ]);

        $older = factory(Report::class)->create([
            'province_id' => $province->id,
            'date' => Carbon::now()->subDay(),
        ]);

        $this->assertEquals($province->latestReport->toArray(), $latest->toArray());
    }

    /**
     * @test
     */
    public function it_can_generate_a_time_series()
    {
        Carbon::setTestNow(Carbon::now());

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
            $province
                ->timeSeries()
                ->pluck('deaths')
                ->toArray(),
        );
        $this->assertEquals(
            [0, 0],
            $province
                ->timeSeries()
                ->pluck('recovered')
                ->toArray(),
        );
    }
}
