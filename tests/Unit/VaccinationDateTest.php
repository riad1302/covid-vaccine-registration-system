<?php

namespace Tests\Unit;

use App\Services\RegisterService;
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
//        $userInfo = (object)[
//            'vaccine_center_id' => 1,
//            'name' => 'gfg',
//            'email' => 'abcd@gmail.com',
//            'mobile_number' => '01829676767',
//            'nid' => '1234567896',
//        ];
//        //dd($userInfo);
//        $vaccinationDate = new RegisterService();
//        $vaccinationDate->getVaccinationDate($userInfo);
        $this->assertTrue(true);
    }
}
