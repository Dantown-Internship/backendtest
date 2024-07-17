<div>
    <div class="flex justify-between items-center my-6">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Recent Transaction Activities') }}
        </h2>

        {{-- <button class="text-indigo-900">view all</button> --}}
    </div>

    <div class="flex flex-col mt-6">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden border-b border-gray-200 rounded-md shadow-md">
                    <table class="min-w-full overflow-x-scroll divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Date & Time
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Activity
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if($transactionActivity->count() > 0)
                                @foreach ($transactionActivity as $item)
                                    <tr class="transition-all hover:bg-gray-100 hover:shadow-lg">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->formatted_date }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">{{ $item->formatted_time }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ ucfirst($item->activity) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="transition-all hover:bg-gray-100 hover:shadow-lg">
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">No Transaction Activity Yet</div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    @isset($transactionActivity)
                        <div class="my-3 px-6">
                            {{ $transactionActivity->links() }}
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
