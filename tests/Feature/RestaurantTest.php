<?php

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('new users are not system admins and use a uuid', function () {
    $user = User::factory()->create();

    expect($user->system_admin)->toBeFalse()
        ->and(Str::isUuid($user->id))->toBeTrue();
});

test('an empty dashboard offers to create a restaurant', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Create a restaurant')
        ->assertDontSee('Admin');
});

test('the dashboard lists only restaurants created by the signed-in user', function () {
    $user = User::factory()->create();
    $own = Restaurant::factory()->create([
        'created_by_id' => $user->id,
        'name' => 'Own Grill',
    ]);
    Restaurant::factory()->create(['name' => 'Other Grill']);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee($own->name)
        ->assertDontSee('Other Grill');
});

test('a user can submit a restaurant that stays pending', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('restaurants.store'), [
            'name' => 'Harbor House',
            'address' => '10 Dock Street',
            'city' => 'Austin',
            'state' => 'TX',
            'zip' => '78701',
            'logo_url' => 'https://example.com/logo.png',
            'website_url' => 'https://example.com',
        ])
        ->assertRedirect(route('dashboard'));

    $restaurant = Restaurant::query()->first();

    expect($restaurant->created_by_id)->toBe($user->id)
        ->and($restaurant->status)->toBe('P')
        ->and($restaurant->approved_at)->toBeNull()
        ->and(Str::isUuid($restaurant->id))->toBeTrue();
});

test('a normal user cannot open the admin restaurant table', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.restaurants.index'))
        ->assertForbidden();
});

test('the admin table lists pending restaurants first and can be searched', function () {
    $admin = User::factory()->create(['system_admin' => true]);
    $owner = User::factory()->create();
    Restaurant::factory()->approved()->create([
        'created_by_id' => $owner->id,
        'name' => 'Approved Place',
        'city' => 'Dallas',
        'state' => 'TX',
    ]);
    Restaurant::factory()->create([
        'created_by_id' => $owner->id,
        'name' => 'Pending Place',
        'city' => 'Austin',
        'state' => 'TX',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.restaurants.index'));

    $response->assertOk()
        ->assertSee($owner->full_name)
        ->assertDontSee($owner->id);
    expect(strpos($response->getContent(), 'Pending Place'))
        ->toBeLessThan(strpos($response->getContent(), 'Approved Place'));

    $this->actingAs($admin)
        ->get(route('admin.restaurants.index', ['name' => 'Pending']))
        ->assertSee('Pending Place')
        ->assertDontSee('Approved Place');

    $this->actingAs($admin)
        ->get(route('admin.restaurants.index', ['city' => 'Dallas']))
        ->assertSee('Approved Place')
        ->assertDontSee('Pending Place');

    $this->actingAs($admin)
        ->get(route('admin.restaurants.index', ['state' => 'TX', 'status' => 'P']))
        ->assertSee('Pending Place')
        ->assertDontSee('Approved Place');

    $this->actingAs($admin)
        ->get(route('admin.restaurants.index', ['created_by_id' => $owner->id]))
        ->assertSee('Pending Place')
        ->assertSee('Approved Place');
});

test('a system admin can approve a pending restaurant', function () {
    $admin = User::factory()->create(['system_admin' => true]);
    $restaurant = Restaurant::factory()->create(['name' => 'Needs Approval']);

    $this->actingAs($admin)
        ->post(route('admin.restaurants.approve', $restaurant))
        ->assertRedirect();

    $restaurant->refresh();

    expect($restaurant->status)->toBe('A')
        ->and($restaurant->approved_at)->not->toBeNull();
});
