@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Pending Transactions') }}</div>

                <div class="card-body">
                    @if ($transactions->isEmpty())
                        <p class="text-center">No pending transactions found.</p>
                    @else
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User ID</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->id }}</td>
                                        <td>{{ $transaction->user_id }}</td>
                                        <td>{{ ucfirst($transaction->type) }}</td>
                                        <td>${{ number_format($transaction->amount, 2) }}</td>
                                        <td>{{ $transaction->description }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <form action="{{ route('transactions.approve', $transaction->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">Approve</button>
                                                </form>
                                                <form action="{{ route('transactions.reject', $transaction->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">Reject</button>
                                                </form>
                                            </div>
                                        </td>
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
