<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Core web routes should be registered.
     */
    public function test_core_routes_are_registered(): void
    {
        $this->assertTrue(Route::has('dashboard'));
    }
}
