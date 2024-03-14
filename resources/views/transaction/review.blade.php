<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Review Transaction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in as a $user->role!") }}
                    
                    <div class="px-6 py-2 text-white">
                        <table class="w-full table-auto border-collapse border border-slate-600 whitespace-nowrap">
                            <thead>
                                <tr class="font-bold">
                                    <th class="border border-slate-300 p-2">Third party</hd>
                                    <th class="border border-slate-300 p-2">Amount</th>
                                    <th class="border border-slate-300 p-2">Type</th>
                                    <th class="border border-slate-300 p-2">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-slate-300 p-2">{{ $transaction->third_party }}</td>
                                    <td class="border border-slate-300 p-2">{{ $transaction->amount }}</td>
                                    <td class="border border-slate-300 p-2">{{ $transaction->type }}</td>
                                    <td class="border border-slate-300 p-2">{{ $transaction->description }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <form method="POST" action="{{ route('transaction.review', ['transaction' => $transaction->id]) }}">
                        @csrf

                        <!-- Decision -->
                        <div class="mt-4">
                            <x-input-label for="decision" :value="__('Decisions')" />
                            <select class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="status" id="decision" required>
                                @foreach($decisions as $decision)
                                    <option value="{{ $decision }}" {{ old('decision') === $decision ? 'selected' : '' }}>
                                        {{ $decision }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('decision')" class="mt-2" />
                        </div>

                        <!-- Note -->
                        <div class="mt-4">
                            <x-input-label for="note" :value="__('Note')" />
                            <textarea id="note" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="note" cols="30" rows="5">{{ old('note') }}</textarea>
                            <x-input-error :messages="$errors->get('note')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Review Transaction') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
