<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Notifications\MakerToCheckerUpdated;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithoutUrlPagination, WithPagination;

    public $id;

    /**
     * Set user id for approval.
     */
    public function approve($id)
    {
        $this->id = $id;
    }

    /**
     * Reset user id.
     */
    public function resetUserAction()
    {
        $this->reset('id');
    }

    /**
     * Confirm the user action for user role update.
     */
    public function confirmUserAction()
    {
        $user = User::find($this->id);
        $this->authorize('update', $user);

        $user->setUserAsChecker();
        $user->save();

        $user->notify(new MakerToCheckerUpdated());

        $this->dispatch('close-user-modal');
    }

    /**
     * Get users that is not an admin.
     */
    public function getUsers(): Paginator
    {
        return User::whereNot('role', 'admin')->orderBy('created_at', 'DESC')->paginate(10);
    }

    /**
     * Render the view for the admin user list.
     */
    public function render(): View
    {
        $this->authorize('viewAny', User::class);

        return view('livewire.admin.user-list', ['users' => $this->getUsers()]);
    }
}
