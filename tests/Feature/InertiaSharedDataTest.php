<?php

namespace Tests\Feature;

use App\Country;
use App\Province;
use PHPUnit\Framework\Constraint\Count;
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

        $this->get('/timeline')->assertViewHas('page.props.countries', [
            (object) [
                'id' => $country->id,
                'name' => $country->name,
                'type' => Country::class,
            ],
        ]);
    }

    /**
     * @test
     */
    public function it_shares_the_provinces()
    {
        $province = factory(Province::class)->create();

        $this->get('/timeline')->assertViewHas('page.props.provinces', [
            (object) [
                'id' => $province->id,
                'name' => $province->name,
                'type' => Province::class,
            ],
        ]);
    }
}
