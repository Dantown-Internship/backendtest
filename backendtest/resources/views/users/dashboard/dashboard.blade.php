<x-app-layout>
  {{--   <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}

   <div class="container">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg py-3">
                <div class="row">
                    @can('system_pool view')
                    <div class="col-6 col-md-4">
                        <div class="card">
                            <div class="card-body">
                              <h5 class="card-title">System Pooling Balance</h5>
                              <h4>N{{ number_format($pool_balance,2) }}</h4>
                            </div>
                          </div>
                    </div>
                    @endcan
                    <div class="col-6 col-md-4">
                        <div class="card">
                            <div class="card-body">
                              <h5 class="card-title">Wallet Balance</h5>
                              <h4>N{{ number_format(auth()->user()->wallet->balance,2) }}</h4>
                            </div>
                          </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="card">
                            <div class="card-body">
                              <h5 class="card-title">Transactions</h5>
                              @can('approve transactions')
                                <h6>({{ $transactions->where('status','pending')->count() }}) Pending Tranaction</h6>
                              @endcan
                                @can('create transactions')
                                    <h6>({{ $transactions->where('status','rejected')->count() }}) Required Modification</h6>
                                @endcan
                            </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>
   </div>




   <div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-6">
                    <h4>({{ count($transactions) }}) Transactions</h4>
                </div>
                <div class="col-6 text-end">
                    @can('create transactions')
                        <a href="{{ route('transaction.create') }}" class="btn btn-info">Create Transaction</a>
                    @endcan
                </div>
            </div>

            <div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>SN</th>
                <th>User</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Descrip.</th>
                <th>Updated By</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
          @php($sn=1)
            @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $sn++ }}</td>
                <td>{{ $transaction->user->name }}</td>
                <td>{{ $transaction->type }}</td>
                <td>N{{ $transaction->amount }}</td>
                <td>{{ $transaction->description }}</td>
                <td>{{ $transaction->approved_by?->name }}</td>
                <td>
                    @can('approve transactions')
                        @if($transaction->status=='pending')
                            <form action="{{ route('transaction.approve',$transaction->id) }}" method="post">
                                @csrf
                                <button type="submit"  class="btn btn-success badge" onclick="return confirm('are you sure you want to Approve?')">Approve</button>
                            </form>
                            <form action="{{ route('transaction.reject',$transaction->id) }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-danger badge" onclick="return confirm('are you sure you want to Reject?')">Reject</button>
                            </form>
                        @else
                            <button class="btn btn-info badge">{{ $transaction->status }}</button>
                        @endif
                    @endcan
                    @cannot('approve transactions')
                        @if($transaction->status=='rejected')
                            <a class="btn btn-danger badge" href="{{ route('transaction.edit',$transaction->id) }}">Require Modification</button>
                        @else
                            <button class="btn btn-info badge">{{ $transaction->status }}</button>
                        @endif 
                    @endcannot
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
        </div>
        </div>
    </div>
   </div>





</x-app-layout>
