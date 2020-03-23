<?php

namespace Tests\Feature;

use App\Country;
use App\Province;
use App\Report;
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
    }

    /**
     * @test
     */
    public function it_has_reports()
    {
        $country = factory(Country::class)->create();

        $reports = factory(Report::class, 2)->create([
            'country_id' => $country->id,
            'province_id' => $country->provinces()->first()->id,
        ]);

        $this->assertEquals(2, $country->reports()->count());
    }
}
