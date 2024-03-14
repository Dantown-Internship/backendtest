<?php

it('takes guest user to login page', function () {
    $response = $this->get('/');
    $response->assertStatus(302);
    $response->assertRedirect('/login');
});
