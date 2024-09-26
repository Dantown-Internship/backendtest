<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $this->seed();

    $response = $this->post('/login', [
        'email' => User::first()->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('transactions.index', absolute: false));
});

test('users can not authenticate with invalid password', function () {

    $this->seed();

    $this->post('/login', [
        'email' => User::first()->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {

    $this->seed();

    $user = User::first();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
