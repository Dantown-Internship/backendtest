<?php

namespace App\Livewire\Admin;

use App\Models\Transaction;
use App\Providers\TransactionUpdated;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class TransactionHistory extends Component
{
    use WithoutUrlPagination, WithPagination;

    public $status;

    public $id;

    public $action;

    /**
     * Set transaction action and id for approval.
     */
    public function approve($id)
    {
        $this->action = 'approve';
        $this->id = $id;
    }

    /**
     * Set transaction action and id for rejection.
     */
    public function reject($id)
    {
        $this->action = 'reject';
        $this->id = $id;
    }

    /**
     * Reset transaction action and id.
     */
    public function resetUserAction()
    {
        $this->reset('action', 'id');
    }

    /**
     * Confirm the user action for a transaction.
     */
    public function confirmUserAction()
    {
        $transaction = Transaction::find($this->id);
        $this->authorize('update', $transaction);

        if ($this->action === 'approve') {
            $transaction->approve();
            $transaction->save();

            event(new TransactionUpdated('approved', $transaction));
        } else {
            $transaction->reject();
            $transaction->save();

            event(new TransactionUpdated('rejected', $transaction));
        }

        $this->dispatch('close-transaction-modal');
        $this->dispatch('update-total-frontend');
    }

    /**
     * Get the transaction history with user details based on status.
     */
    public function getTransactionHistory(): Paginator
    {
        return Transaction::with('user')
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
     * Render the view for transaction history.
     */
    public function render(): View
    {
        return view('livewire.admin.transaction-history', ['transactions' => $this->getTransactionHistory()]);
    }
}
