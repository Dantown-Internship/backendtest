<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 gap-5 my-6 sm:grid-cols-2 lg:grid-cols-4">

                    <div class="col-span-2 p-4 transition-shadow border rounded-lg shadow-sm hover:shadow-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex flex-col space-y-2">
                                <span class="text-gray-400">Total Transactions</span>
                                <span class="text-4xl font-bold">{{ $total_transactions }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-2 p-4 transition-shadow border rounded-lg shadow-sm hover:shadow-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex flex-col space-y-2">
                                <span class="text-gray-400">Balance</span>
                                @if ($auth_user->hasWallet())
                                    <span class="text-4xl font-bold">{{ $total_balance }}</span>
                                @else
                                <form method="POST" action="{{ route('wallet.create') }}">
                                    @csrf
                                    <input name="id" type="hidden" value="{{ $auth_user->id }}" />
                                    <x-primary-button class="ms-4">
                                        {{ __('Create Wallet') }}
                                    </x-primary-button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <h2 class="font-semibold text-xl text-gray-800 leading-tight py-3">
                    {{ __('Create Transaction') }}
                </h2>

                <div class="">
                    <form method="POST" action="{{ route('transaction.create') }}">
                        @csrf

                        <!-- Type -->
                        <div>
                            <x-input-label for="type" :value="__('Type')" />
                            <x-select-input id="type" class="block mt-1 w-full" type="text" name="type"
                                :options="[
                                    'deposit' => 'Deposit',
                                    'data' => 'Data',
                                    'airtime' => 'Airtime',
                                    'electricity' => 'Electricity',
                                    'water' => 'Water',
                                ]" :value="old('type')" required autofocus />
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <!-- Amount -->
                        <div class="mt-4">
                            <x-input-label for="amount" :value="__('Amount')" />
                            <x-text-input id="amount" class="block mt-1 w-full" type="number" name="amount"
                                :value="old('amount')" required />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />

                            <x-text-input id="description" class="block mt-1 w-full" type="text" name="description"
                                required />

                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">

                            <x-primary-button class="ms-4">
                                {{ __('Create Transaction') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
        
                <livewire:user.transaction-history />
            </div>
        </div>
    </div>
</x-app-layout>
