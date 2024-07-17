<div>
    <div class="flex justify-between items-center my-6">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaction History') }}
        </h2>

        <select wire:model.live="status"
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="all">All</option>
            <option value="approved">Approved</option>
            <option value="pending">Pending</option>
            <option value="rejected">Rejected</option>
        </select>
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
                                    Type & Desc.
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Amount
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if($transactions->count() > 0)
                                @foreach ($transactions as $item)
                                    <tr class="transition-all hover:bg-gray-100 hover:shadow-lg">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ ucfirst($item->type) }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ strtolower($item->description) }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ $item->formatted_amount }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex px-2 text-xs font-semibold leading-5
                                                @if ($item->status == 'approved') text-green-800 bg-green-100
                                                @elseif ($item->status == 'pending')
                                                 text-yellow-800 bg-yellow-100
                                                @elseif ($item->status == 'rejected')
                                                 text-red-800 bg-red-100 @endif
                                                  rounded-full">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="transition-all hover:bg-gray-100 hover:shadow-lg">
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">No Transaction Yet</div>
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                    @isset($transactions)
                        <div class="my-3 px-6">
                            {{ $transactions->links() }}
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
