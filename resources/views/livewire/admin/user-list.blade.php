<div>

    <div class="flex justify-between items-center my-6">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User List') }}
        </h2>

        {{-- <a href="{{ route('admin.users') }}" class="text-indigo-900">view all</a> --}}
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
                                    Name
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Role
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if($users->count() > 0)
                                @foreach ($users as $item)
                                    <tr class="transition-all hover:bg-gray-100 hover:shadow-lg">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ ucwords($item->name) }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">{{ strtolower($item->email) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ ucfirst($item->role) }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold whitespace-nowrap">
                                            @if (!$item->isChecker())
                                                <button class="text-green-900"
                                                    x-on:click.prevent="$dispatch('open-modal', 'confirm-checker-action')"
                                                    wire:click="approve({{ $item->id }})">Make checker</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="transition-all hover:bg-gray-100 hover:shadow-lg">
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">No Users Yet</div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    @isset($users)
                        <div class="my-3 px-6">
                            {{ $users->links() }}
                        </div>
                    @endisset
                </div>

                <x-modal name="confirm-checker-action" focusable>
                    <div class="p-10">
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ 'Are you sure you want to make this user a checker?' }}
                        </h2>

                        <div x-data class="mt-6 flex justify-end" wire:loading.class="hidden"
                            wire:target="approve, reject">
                            <x-secondary-button id="close-user-modal" x-on:click="$dispatch('close')"
                                wire:click="resetUserAction">
                                {{ __('No') }}
                            </x-secondary-button>
                            @script
                                <script>
                                    $wire.on('close-user-modal', () => {
                                        document.querySelector('#close-user-modal').click()
                                    });
                                </script>
                            @endscript

                            <x-primary-button class="ms-3" wire:click="confirmUserAction">
                                <span wire:loading wire:target="confirmUserAction" class="hidden">
                                    <x-loader class="animate-spin inline-block -ml-1 mr-3 h-5 w-5 text-black" />
                                </span>
                                {{ __('Yes') }}
                            </x-primary-button>
                        </div>
                    </div>
                </x-modal>
            </div>
        </div>
    </div>
</div>
