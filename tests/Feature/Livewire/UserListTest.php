<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Admin\UserList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function renders_successfully_for_admin_only()
    {
        Livewire::actingAs(User::factory()->admin()->create())
            ->test(UserList::class)
            ->assertStatus(200);
    }

    public function test_does_not_render_successfully_for_checker_and_maker()
    {
        Livewire::actingAs(User::factory()->checker()->create())
            ->test(UserList::class)
            ->assertForbidden();

        Livewire::actingAs(User::factory()->maker()->create())
            ->test(UserList::class)
            ->assertForbidden();
    }
}
