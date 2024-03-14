<?php

namespace Tests\Feature;

use App\Models\SystemPool;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

uses(RefreshDatabase::class);

it('can credit balance', function() {
    $systemPool = SystemPool::create(config('constant.system_pool'));

    $systemPool->creditBalance(50.00);

    $this->assertEquals(1050.00, $systemPool->refresh()->balance);
});

it('can debit balance', function() {
    $systemPool = SystemPool::create(config('constant.system_pool'));

    $systemPool->debitBalance(50.00);

    $this->assertEquals(950.00, $systemPool->refresh()->balance);
});