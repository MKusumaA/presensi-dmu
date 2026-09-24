<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Memastikan pengunjung yang tidak memiliki sesi aktif
     * selalu dialihkan ke gerbang login.
     */
    public function test_the_application_redirects_unauthenticated_users_to_login(): void
    {
        $response = $this->get('/');

        // Mengharapkan status 302 (Redirect) alih-alih 200 (OK)
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}