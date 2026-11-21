<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Step1FoundationTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role, bool $active = true): User
    {
        return User::factory()->create(['role' => $role, 'is_active' => $active]);
    }

    public function test_guests_are_sent_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->get('/')->assertRedirect('/dashboard');
    }

    public function test_self_registration_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_dashboard_shows_summary_for_both_roles(): void
    {
        foreach ([User::ROLE_OWNER, User::ROLE_CARETAKER] as $role) {
            $this->actingAs($this->user($role))->get('/dashboard')
                ->assertOk()
                ->assertSee('Total rooms')
                ->assertSee('Low stock')
                ->assertSee('Recent rental payments');
        }
    }

    public function test_user_management_is_owner_only(): void
    {
        $this->actingAs($this->user(User::ROLE_CARETAKER))->get('/users')->assertForbidden();
        $this->actingAs($this->user(User::ROLE_OWNER))->get('/users')->assertOk();
    }

    public function test_sidebar_hides_user_management_from_caretaker(): void
    {
        $this->actingAs($this->user(User::ROLE_CARETAKER))->get('/dashboard')->assertDontSee('User Management');
        $this->actingAs($this->user(User::ROLE_OWNER))->get('/dashboard')->assertSee('User Management');
    }

    public function test_every_sidebar_link_resolves(): void
    {
        $this->actingAs($owner = $this->user(User::ROLE_OWNER));
        foreach (['inventory.index', 'residents.index', 'rooms.index', 'waiting-list.index', 'payments.index', 'users.index', 'profile.edit'] as $name) {
            $this->get(route($name))->assertOk();
        }
    }

    public function test_deactivated_users_are_logged_out(): void
    {
        $this->actingAs($this->user(User::ROLE_CARETAKER, active: false))
            ->get('/dashboard')->assertRedirect(route('login'));
    }
}
