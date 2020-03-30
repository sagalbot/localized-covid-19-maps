<?php

namespace Tests\Feature;

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
}
