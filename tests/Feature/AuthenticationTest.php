<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

uses(RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $this->get('/')->assertRedirect(route('login'));
});

test('users cannot authenticate with an invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can authenticate and reach home', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('guests return to the original link after login', function () {
    $user = User::factory()->create();

    $this->get('/dashboard?from=link')->assertRedirect(route('login'));

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect('/dashboard?from=link');
});

test('guests return to the original link after registration', function () {
    $this->get('/dashboard?from=link')->assertRedirect(route('login'));

    $this->post('/register', registrationFields())->assertRedirect('/dashboard?from=link');

    $this->assertAuthenticated();
});

test('registration rejects a duplicate email', function () {
    User::factory()->create(['email' => 'ada@example.com']);

    $this->post('/register', registrationFields())->assertSessionHasErrors('email');
});

test('registration rejects a normalized duplicate name with the same birth date and state', function () {
    User::factory()->create([
        'first_name' => 'John',
        'last_name' => 'Smith',
        'date_of_birth' => '1990-01-01',
        'state' => 'TX',
    ]);

    $this->post('/register', registrationFields([
        'first_name' => 'john',
        'last_name' => 'smith',
        'date_of_birth' => '1990-01-01',
        'state' => 'TX',
        'email' => 'other@example.com',
    ]))->assertSessionHasErrors([
        'first_name' => 'An account with these details already exists.',
    ]);

    $this->assertGuest();
});

test('registration rejects a close or similar sounding name with the same birth date and state', function () {
    User::factory()->create([
        'first_name' => 'John',
        'last_name' => 'Smith',
        'date_of_birth' => '1990-01-01',
        'state' => 'TX',
    ]);

    $this->post('/register', registrationFields([
        'first_name' => 'Jon',
        'last_name' => 'Smyth',
        'date_of_birth' => '1990-01-01',
        'state' => 'TX',
        'email' => 'other@example.com',
    ]))->assertSessionHasErrors('first_name');

    $this->assertGuest();
});

test('registration allows the same name with a different birth date', function () {
    User::factory()->create([
        'first_name' => 'John',
        'last_name' => 'Smith',
        'date_of_birth' => '1990-01-01',
        'state' => 'TX',
    ]);

    $this->post('/register', registrationFields([
        'first_name' => 'John',
        'last_name' => 'Smith',
        'date_of_birth' => '1991-01-01',
        'state' => 'TX',
        'email' => 'other@example.com',
    ]))->assertRedirect(route('dashboard'));
});

test('registration allows the same name in a different state', function () {
    User::factory()->create([
        'first_name' => 'John',
        'last_name' => 'Smith',
        'date_of_birth' => '1990-01-01',
        'state' => 'TX',
    ]);

    $this->post('/register', registrationFields([
        'first_name' => 'John',
        'last_name' => 'Smith',
        'date_of_birth' => '1990-01-01',
        'state' => 'CA',
        'email' => 'other@example.com',
    ]))->assertRedirect(route('dashboard'));
});

test('registration allows the same first name with a different last name', function () {
    User::factory()->create([
        'first_name' => 'John',
        'last_name' => 'Smith',
        'date_of_birth' => '1990-01-01',
        'state' => 'TX',
    ]);

    $this->post('/register', registrationFields([
        'first_name' => 'John',
        'last_name' => 'Jones',
        'date_of_birth' => '1990-01-01',
        'state' => 'TX',
        'email' => 'other@example.com',
    ]))->assertRedirect(route('dashboard'));
});

/**
 * @param  array<string, string>  $overrides
 * @return array<string, string>
 */
function registrationFields(array $overrides = []): array
{
    return [
        'first_name' => 'Ada',
        'last_name' => 'Lovelace',
        'date_of_birth' => '1815-12-10',
        'state' => 'NY',
        'email' => 'ada@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        ...$overrides,
    ];
}

test('a reset link is sent and the new password works', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email])
        ->assertSessionHas('status', __(Password::RESET_LINK_SENT));

    $token = null;

    Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use (&$token) {
        $token = $notification->token;

        return true;
    });

    $this->post('/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ])->assertRedirect(route('login'));

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'new-password',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('an unknown email gets the same reset response', function () {
    Notification::fake();

    $this->post('/forgot-password', ['email' => 'missing@example.com'])
        ->assertSessionHas('status', __(Password::RESET_LINK_SENT));

    Notification::assertNothingSent();
});
