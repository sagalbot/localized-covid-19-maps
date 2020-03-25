<?php

namespace Tests\Feature;

use App\Console\Commands\ScrapeAndSeed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ScrapeAndSeedTest extends TestCase
{
    use WithFaker;

    /**
     * @var \App\Console\Commands\ScrapeAndSeed
     */
    protected $command;

    public function setUp(): void
    {
        parent::setUp();

        $this->command = resolve(ScrapeAndSeed::class);
    }

    /**
     * @test
     */
    public function it_can_find_the_province_name()
    {
        $province = $this->faker->state;

        $this->assertEquals($province, $this->command->getProvinceName(['Province/State' => $province]));
        $this->assertEquals($province, $this->command->getProvinceName(['Province_State' => $province]));
        $this->assertEquals('', $this->command->getProvinceName(['wrong key' => $province]));
    }

    /**
     * @test
     */
    public function it_can_find_the_country_name()
    {
        $country = $this->faker->country;

        $this->assertEquals($country, $this->command->getCountryName(['Country/Region' => $country]));
        $this->assertEquals($country, $this->command->getCountryName(['Country_Region' => $country]));
        $this->assertEquals('', $this->command->getProvinceName(['wrong key' => $country]));
    }
}
