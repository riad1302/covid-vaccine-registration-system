<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\VaccinationDateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class VaccinationDateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_vaccination_date_generate(): void
    {
        $userInfo = User::factory()->create([
            'name' => 'riad'
        ]);
        dd($userInfo);
        $vaccinationDate = new VaccinationDateService();
        $vaccinationDate->getVaccinationDate($userInfo);
        $this->assertTrue(true);
    }
}
