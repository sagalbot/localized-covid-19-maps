<?php

namespace Tests\Feature;

use App\Country;
use App\Province;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InertiaSharedDataTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_shares_the_countries()
    {
        $country = factory(Country::class)->create();

        $this->get('/')->assertViewHas('page.props.countries', (object) [
            'data' => [
                (object) [
                    'id'   => $country->id,
                    'name' => $country->name,
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function it_shares_the_provinces()
    {
        $province = factory(Province::class)->create();

        $this->get('/')->assertViewHas('page.props.provinces', (object) [
            'data' => [
                (object) [
                    'id'         => $province->id,
                    'name'       => $province->name,
                    'country_id' => $province->country->id,
                ],
            ],
        ]);
    }
}
