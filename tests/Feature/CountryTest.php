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
}
