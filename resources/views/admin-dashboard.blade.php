<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 gap-5 my-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="p-4 transition-shadow border rounded-lg shadow-sm hover:shadow-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex flex-col space-y-2">
                                <span class="text-gray-400">Total Users</span>
                                <span class="text-4xl font-bold">{{ $total_users }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 transition-shadow border rounded-lg shadow-sm hover:shadow-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex flex-col space-y-2">
                                <span class="text-gray-400">Total Transactions</span>
                                <span class="text-4xl font-bold">{{ $total_transactions }}</span>
                            </div>
                        </div>
                    </div>

                    <livewire:admin.balance />
                </div>
                @if (Auth::user()->isAdmin())
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight py-3">
                        {{ __('Create Checker') }}
                    </h2>

                    <div class="">
                        <form method="POST" action="{{ route('admin.users.create') }}">
                            @csrf

                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                    :value="old('name')" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Email Address -->
                            <div class="mt-4">
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                    :value="old('email')" required autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button class="ms-4">
                                    {{ __('Create Checker') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    <livewire:admin.user-list />
                @endif

                <livewire:admin.transaction-history />

                <livewire:admin.transaction-activity />
            </div>
        </div>
    </div>
</x-app-layout>
