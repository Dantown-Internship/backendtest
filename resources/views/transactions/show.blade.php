@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Wallet Management') }}</div>

                <div class="card-body">
                    @if ($wallet)
                        <div class="alert alert-info">
                            Balance: ${{ number_format($wallet->balance, 2) }}
                        </div>
                    @else
                        <div class="alert alert-warning">
                            Wallet information is not available.
                        </div>
                    @endif

                    <h2>Approved Transactions</h2>
                    @if ($approvedTransactions->isEmpty())
                        <div class="alert alert-secondary" role="alert">
                            No approved transactions found.
                        </div>
                    @else
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($approvedTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->id }}</td>
                                        <td>{{ ucfirst($transaction->type) }}</td>
                                        <td>${{ number_format($transaction->amount, 2) }}</td>
                                        <td>{{ $transaction->description }}</td>
                                        <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
