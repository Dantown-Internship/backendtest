<?php

namespace Tests\Feature;

use App\Http\Controllers\WalletController;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;

uses(RefreshDatabase::class);

it('displays wallet view with authenticated user', function () {
    $user = User::factory()->create();
    $user->wallet()->save((new Wallet));
    $this->actingAs($user);

    $response = $this->get(route('wallet'));

    $response->assertStatus(200)
             ->assertViewIs('wallet')
             ->assertViewHas('user', $user);
});