<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Notifications\NewCheckerAccountCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UserListTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\AdminSeeder::class);
    }

    public function test_admin_can_see_create_user_form(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $response = $this->get(route('admin.dashboard'));

        $response->assertSee(['Create Checker', 'Email']);
    }

    public function test_admin_can_create_checker_account(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        Notification::fake();

        $response = $this->post(route('admin.users.create'), [
            'email' => 'testq@example.com',
            'name' => 'Checker',
        ]);

        $user = User::where('email', 'testq@example.com')->first();

        Notification::assertSentTo($user, NewCheckerAccountCreated::class);

        $this->assertDatabaseHas('users', [
            'email' => 'testq@example.com',
            'name' => 'Checker',
            'role' => 'checker',
        ]);
    }

    public function test_admin_can_see_make_checker_button_on_user_list_table(): void
    {
        $maker = User::factory()->maker()->create();

        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $response = $this->get(route('admin.dashboard'));

        $response->assertSee(['User List', 'Make checker', strtolower($maker->email), ucwords($maker->name)]);
    }
}
