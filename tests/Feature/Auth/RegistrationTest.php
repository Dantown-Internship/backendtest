<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {

    $this->seed();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@marker.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('transactions.index', absolute: false));
});
