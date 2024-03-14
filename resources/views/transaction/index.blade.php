<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between ites-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Transaction') }}
            </h2>

            <div>
                <x-nav-link :href="route('transaction.create')">
                    {{ __('Create') }}
                </x-nav-link>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in as a $user->role!") }}
                    <p>You have {{ $user->role === $checker ? 'reviewed ' . count($user->transactions) : 'made ' . count($user->transactions) }} transaction{{ count($user->transactions) > 1 ? 's':''}}</p>
                </div>

                @if(auth()->user()->role === config('constant.role.checker'))
                    @if (count($transactions))
                        <div class="px-6 py-2 text-white">
                            <table class="w-full table-auto border-collapse border border-slate-600 whitespace-nowrap">
                                <thead>
                                    <tr class="font-bold">
                                        <th class="border border-slate-300 p-2">Third party</hd>
                                        <th class="border border-slate-300 p-2">Amount</th>
                                        <th class="border border-slate-300 p-2">Type</th>
                                        <th class="border border-slate-300 p-2">Description</th>
                                        <th class="border border-slate-300 p-2">Status</th>
                                        <th class="border border-slate-300 p-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td class="border border-slate-300 p-2">{{ $transaction->third_party }}</td>
                                            <td class="border border-slate-300 p-2">{{ $transaction->amount }}</td>
                                            <td class="border border-slate-300 p-2">{{ $transaction->type }}</td>
                                            <td class="border border-slate-300 p-2">{{ $transaction->description }}</td>
                                            <td class="border border-slate-300 p-2">{{ $transaction->status }}</td>
                                            <td class="border border-slate-300 p-2">
                                            @if($user->role === $maker)
                                                    <a class="py-1 px-3 rounded {{ $transaction->status === $rejected ? 'bg-red-500 hover:bg-gray-700' : 'bg-gray-500 hover:bg-gray-700' }}" href="{{ route('transaction.edit', ['transaction' => $transaction->id]) }}">Edit</a>
                                                @endif
                                                @if($user->role === $checker)
                                                <a class="py-1 px-3 rounded {{ $transaction->status === $pending ? 'bg-red-500 hover:bg-gray-700' : 'bg-gray-500 hover:bg-gray-700' }}" href="{{ route('transaction.review', ['transaction' => $transaction->id]) }}">Review</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="px-6 py-2 text-white">
                            {{ __('There is no transaction') }}
                        </div>
                    @endif
                @else
                    @if (count($user->transactions))
                        <div class="px-6 py-2 text-white">
                            <table class="w-full table-auto border-collapse border border-slate-600 whitespace-nowrap">
                                <thead>
                                    <tr class="font-bold">
                                        <th class="border border-slate-300 p-2">Third party</hd>
                                        <th class="border border-slate-300 p-2">Amount</th>
                                        <th class="border border-slate-300 p-2">Type</th>
                                        <th class="border border-slate-300 p-2">Description</th>
                                        <th class="border border-slate-300 p-2">Status</th>
                                        <th class="border border-slate-300 p-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->transactions as $transaction)
                                        <tr>
                                            <td class="border border-slate-300 p-2">{{ $transaction->third_party }}</td>
                                            <td class="border border-slate-300 p-2">{{ $transaction->amount }}</td>
                                            <td class="border border-slate-300 p-2">{{ $transaction->type }}</td>
                                            <td class="border border-slate-300 p-2">{{ $transaction->description }}</td>
                                            <td class="border border-slate-300 p-2">{{ $transaction->status }}</td>
                                            <td class="border border-slate-300 p-2">
                                                @if($user->role === $maker)
                                                    <a class="py-1 px-3 rounded {{ $transaction->status === $rejected ? 'bg-red-500 hover:bg-gray-700' : 'bg-gray-500 hover:bg-gray-700' }}" href="{{ route('transaction.edit', ['transaction' => $transaction->id]) }}">Edit</a>
                                                @endif
                                                @if($user->role === $checker)
                                                <a class="py-1 px-3 rounded {{ $transaction->status === $pending ? 'bg-red-500 hover:bg-gray-700' : 'bg-gray-500 hover:bg-gray-700' }}" href="{{ route('transaction.review', ['transaction' => $transaction->id]) }}">Review</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="px-6 py-2 text-white">
                            {{ __('You have made no transaction') }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
