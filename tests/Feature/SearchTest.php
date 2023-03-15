<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class SearchTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_search_screen_render(): void
    {
        $response = $this->get('/search');

        $response->assertStatus(200);
    }

    public function test_search_status(): void
    {
        $userInfo = User::factory()->create();

        $response = $this->actingAs($userInfo)->get('/search-status/?nid='.$userInfo->nid);

        $response->assertStatus(200);
    }
}
