<?php

namespace App\Livewire\User;

use App\Models\Transaction;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class TransactionHistory extends Component
{
    use WithoutUrlPagination, WithPagination;

    public $status;

    /**
     * Retrieves the transaction history for the authenticated user based on status filters.
     */
    public function getTransactionHistory(): Paginator
    {
        return Transaction::where('user_id', auth()->user()->id)
            ->when($this->status, function ($query) {
                switch ($this->status) {
                    case 'approved':
                        $query->approved();
                        break;
                    case 'pending':
                        $query->pending();
                        break;
                    case 'rejected':
                        $query->rejected();
                        break;
                    default:
                        break;
                }
            })->orderBy('created_at', 'DESC')->paginate(10);
    }

    /**
     * Render the view for the transaction history.
     */
    public function render(): View
    {
        return view('livewire.user.transaction-history', ['transactions' => $this->getTransactionHistory()]);
    }
}
