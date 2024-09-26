<x-app-layout>
   

    <div class="flex flex-col mx-auto items-center font-sans antialiased bg-white dark:bg-gray-900 h-full w-full px-6">
        @session('message')
            <div class="w-full pt-4 container">
                <div class="flex items-start w-80 h-46 border bg-white dark:bg-gray-800 dark:border-gray-500 rounded p-6">
                    <span class="text-gray-900 dark:text-gray-300">{{ session('message') }}</span>
                </div>
            </div>
        @endsession
        <div class="flex mt-10 w-full space-x-8">
          
            @can('marker-actions')
                <div class="max-w-lg min-w-[24rem] w-full border border-gray-500 rounded">
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            <h4 class="text-gray-800 dark:text-gray-300 text-lg font-bold pb-4">Create New Transaction</h4>
                            <form id="form" action="{{ route('transaction.store') }}" method="post">
                                @csrf
                                <div class="flex flex-col pt-6 space-y-4 w-full border-t border-gray-600">
                                    <div class="flex flex-col space-y-2 w-full">
                                        <p class="text-gray-800 font-medium dark:text-gray-300">Select Transaction Type</p>
                                        <select class="px-3 py-2.5 border border-gray-500 focus:bg-transparent dark:border-transparent rounded bg-transparent dark:bg-gray-900 text-gray-800 dark:text-gray-300" name="type" id="type">
                                            <option value="credit">Debit</option>
                                            <option value="debit">Credit</option>
                                        </select>
                                    </div>
                                    @error('type')     
                                        <div class="text-red-300 mt-3 text-sm">{{ $message }}</div>
                                    @enderror
                                
                                    <div class="flex flex-col space-y-2 w-full">
                                        <p class="text-gray-800 font-medium dark:text-gray-300">Enter Transaction Amount</p>
                                        <input name="amount" id="amount" class="focus:bg-transparent disabled:bg-gray-100  dark:disabled:bg-gray-900/50 px-3 py-2.5 active:bg-transparent border border-gray-500 dark:border-transparent rounded bg-transparent dark:bg-gray-900 text-gray-800 dark:text-gray-300" type="number" value="{{ old('amount') }}" placeholder="Enter Transaction Amount">
                                    </div>
                                    @error('amount')     
                                        <div class="text-red-300 mt-3 text-sm">{{ $message }}</div>
                                    @enderror

                                    <div class="flex flex-col space-y-2 w-full">
                                        <p class="text-gray-800 font-medium dark:text-gray-300">Enter Transaction Description</p>
                                        <input id="description" name="description" class="focus:bg-transparent px-3 py-2.5 border border-gray-500 dark:border-transparent rounded bg-transparent dark:bg-gray-900 text-gray-800 dark:text-gray-300"alue="{{ old('description') }}" type="text" placeholder="Enter Transaction description">
                                    </div>
                                    @error('description')     
                                        <div class="text-red-300 mt-3 text-sm">{{ $message }}</div>
                                    @enderror
                                
                                    <div class="w-full pt-4">
                                        <button id="submit" type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 w-full transition-all rounded" >Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan

            <div id="" class="flex flex-col w-full space-y-4">

                <h1 class="text-gray-800 dark:text-gray-300 pb-5 font-bold text-2xl">Transactions</h1>
                <div class="flex justify-between space-x-3 items-center rounded text-lg dark:text-gray-100 text-gray-800 px-4 py-3 border border-gray-500 cursor-pointer dark:bg-gray-800 w-full">
                       
                    <div class="flex w-28">
                        <span class="text-base font-black">Amount</span>
                    </div>

                    <div class="flex w-28">
                        <span class="text-base font-black">Type</span>
                    </div>

                    <div class="flex w-40">
                        <span class="text-base font-black">Description</span>
                    </div>

                    <div class="flex w-28">
                        <span class="text-base font-black">Status</span>
                    </div>

                    <div class="flex w-28">
                        <span class="text-base font-black">Created At</span>
                    </div>

                    <div class="flex w-28">
                        <span class="text-base font-black">Updated At</span>
                    </div>

                    <div class="flex w-28">
                        <span class="text-base font-black">Actions</span>
                    </div>
        
                </div>

              <div class="w-full space-y-1">

                @forelse ( $transactions as $key => $transaction )
        
                    <div class="w-full pt-3">
                        <div class="flex justify-between space-x-3 items-center rounded text-lg dark:text-gray-300 text-gray-700 px-4 py-3 border border-gray-500 cursor-pointer dark:bg-gray-800 w-full">
                            <div class="flex w-28">
                                <span class="text-base font-normal">N{{ number_format( $transaction->amount, 2) }}</span>
                            </div>

                            <div class="flex w-28">
                                <span class="text-base font-normal">{{ $transaction->type }}</span>
                            </div>

                            <div class="flex w-40">
                                <span class="text-base font-normal">{{ $transaction->description }}</span>
                            </div>

                            <div class="flex w-28">
                                <span class="text-base font-normal">{{ $transaction->approval_status }}</span>
                            </div>

                            <div class="flex w-28">
                                <span class="text-base font-normal">{{ $transaction->created_at }}</span>
                            </div>

                            <div class="flex w-28">
                                <span class="text-base font-normal">{{ $transaction->updated_at }}</span>
                            </div>

                            <div class="flex w-28">
                                <div class="flex items-center w-full space-x-2">
                                    @can('marker-actions')
                                       @if ( $transaction->is_rejected )
                                            <button class="flex items-center rounded space-x-3 bg-blue-600 hover:bg-blue-700 transition-all px-4 py-2.5" type="button" onclick="editTransaction('{{ $transaction->id }}', '{{ $transaction->type }}', '{{ $transaction->amount }}', '{{ $transaction->description }}')">
                                                <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </button>
                                       @endif
                                    @endcan
                                    
                                    @can('checker-actions')

                                            @if ( ! $transaction->is_approved )
                                                <form action="{{ route('transaction.approve', $transaction ) }}" method="post">
                                                    @csrf
                                                    @method('PATCH')
                                        
                                                    <button class="flex items-center rounded space-x-3 bg-green-600 hover:bg-green-700 transition-all px-4 py-2.5">
                                                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                                            <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif


                                            @if ( ! $transaction->is_rejected )
                                                <form action="{{ route('transaction.reject', $transaction ) }}" method="post">
                                                    @csrf
                                                    @method('PATCH')
                                        
                                                    <button class="flex items-center rounded space-x-3 bg-red-600 hover:bg-red-700 transition-all px-4 py-2.5">
                                                        <svg class="text-white h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                                            <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                                        </svg>
                                                        
                                                    </button>
                                                </form>
                                            @endif
                                        

                                       
                                    @endcan
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                @empty
            
                    <div class="flex justify-center items-center rounded text-lg dark:text-gray-100 text-gray-800 px-4 py-6 border border-gray-500">
                        <span>No Transactions Available</span>
                    </div>
            
                @endforelse
              </div>
            </div>
        </div>
    </div>

   
   @section('scripts')

       <script>

            const editTransaction = ( transactionId, transactionType, transactionAmount, transactionDescription ) => {

                let form =  document.getElementById('form');
                let type =  document.getElementById('type');
                let amount =  document.getElementById('amount');
                let description =  document.getElementById('description');
                let formButton = document.getElementById('submit');

                let input = document.createElement('input');

                input.setAttribute('type', 'hidden');
                input.setAttribute('name', '_method');
                input.setAttribute('value', 'PATCH');

                type.disabled = transactionType;
                amount.value = transactionAmount;
                type.disabled = true;
                amount.disabled = true;

                description.value = transactionDescription;

                formButton.innerText = 'Update';

                form.appendChild(input);
                form.action = "{{ url('/transaction/update') }}/" + transactionId;
                
            }

       </script>
   @endsection
    

</x-app-layout>