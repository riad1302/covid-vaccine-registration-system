<?php

use App\Models\VaccineCenter;
use App\Providers\RouteServiceProvider;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $vaccine_center = VaccineCenter::factory()->create([
        'name' => 'abcd',
    ]);
    //dd([$vaccine_center->id, $vaccine_center->name]);
    $response = $this->post('/register', [
        'vaccine_center_id' => $vaccine_center->id,
        'name' => 'Test User',
        'email' => 'test@example.com',
        'nid' => '0123456789',
        'mobile_number' => '01829676767',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    //$this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::Register);
});
