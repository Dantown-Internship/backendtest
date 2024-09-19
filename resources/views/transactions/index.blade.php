@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('All Transactions') }}</div>

                <div class="card-body">
                    @if ($transactions->isEmpty())
                        <div class="alert alert-warning" role="alert">
                            No transactions found.
                        </div>
                    @else
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User ID</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->id }}</td>
                                        <td>{{ $transaction->user_id }}</td>
                                        <td>{{ $transaction->type }}</td>
                                        <td>${{ number_format($transaction->amount, 2) }}</td>
                                        <td>{{ $transaction->description }}</td>
                                        <td>{{ ucfirst($transaction->status) }}</td>
                                        <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>{{ $transaction->updated_at->format('Y-m-d H:i:s') }}</td>
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
