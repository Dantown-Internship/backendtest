<?php

namespace App\Livewire\Admin;

use App\Models\Wallet;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Balance extends Component
{
    /**
     * Retrieves the formatted balance of the system wallet with ID 1.
     *
     * This function is triggered when the 'update-total-frontend' event is fired.
     *
     * @return string The formatted balance of the wallet.
     */
    #[On('update-total-frontend')]
    public function getTotalBalance()
    {
        return Wallet::find(1)->formatted_balance;
    }

    /**
     * Renders the livewire component for the admin balance.
     */
    public function render(): View
    {
        return view('livewire.admin.balance', ['total_balance' => $this->getTotalBalance()]);
    }
}
