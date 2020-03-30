<?php

namespace Tests\Feature;

use App\Country;
use App\Report;
use App\Repositories\CountryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \App\Repositories\CountryRepository
     */
    protected $repo;

    public function setUp(): void
    {
        parent::setUp();

        $this->repo = resolve(CountryRepository::class);
    }

    /**
     * @test
     */
    public function it_can_get_all_countries_with_confirmed_cases()
    {
        //$country = factory(Country::class)->create();
        //
        //$report = factory(Report::class, 2)->create([
        //    'country_id' => $country->id,
        //    'province_id' => null,
        //    'confirmed' => 2,
        //]);
        //
        //$this->assertEquals(
        //    [
        //        [
        //            'id' => $country->id,
        //            'name' => $country->name,
        //            'cases' => 4,
        //        ],
        //    ],
        //    $this->repo->withCases()->get(),
        //);
    }
}
