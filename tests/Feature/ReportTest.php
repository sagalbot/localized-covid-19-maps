<?php

namespace Tests\Feature;

use App\Country;
use App\Province;
use App\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_report_has_a_country()
    {
        $report = factory(Report::class)->create();

        $this->assertEquals(1, $report->country()->count());
    }

    /**
     * @test
     */
    public function a_report_has_a_province()
    {
        $report = factory(Report::class)->create();

        $this->assertEquals(1, $report->province()->count());
    }
}
