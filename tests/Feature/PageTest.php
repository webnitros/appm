<?php

namespace Tests\Feature;

use Tests\TestCase;

class PageTest extends TestCase
{
    /** @test */
    public function page()
    {
        $this->postJson('/api/page', [
            'text' => 'password',
        ])
            ->assertSuccessful();
    }

}
