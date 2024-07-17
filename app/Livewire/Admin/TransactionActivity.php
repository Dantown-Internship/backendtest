<?php

namespace App\Livewire\Admin;

use App\Models\TransactionActivity as ModelsTransactionActivity;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class TransactionActivity extends Component
{
    use WithoutUrlPagination, WithPagination;

    /**
     * Get the transaction activities.
     */
    #[On('update-total-frontend')]
    public function getTransactionActivity(): Paginator
    {
        return ModelsTransactionActivity::orderBy('created_at', 'DESC')->paginate(10);
    }

    /**
     * Render the view for the admin transaction activity.
     */
    public function render(): View
    {
        return view('livewire.admin.transaction-activity', ['transactionActivity' => $this->getTransactionActivity()]);
    }
}
