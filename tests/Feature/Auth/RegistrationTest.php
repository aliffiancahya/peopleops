<?php

it('does not permit uninvited public registrations', function () {
    $this->get('/register')->assertNotFound();
    $this->post('/register', [
        'name' => 'Uninvited person', 'email' => 'uninvited@example.test',
        'password' => 'Password123!', 'password_confirmation' => 'Password123!',
    ])->assertNotFound();
    $this->assertGuest();
});
