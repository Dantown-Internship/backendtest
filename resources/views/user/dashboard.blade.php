@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="alert alert-success alert-dismissible fade d-none" role="alert" id="successAlert">

        </div>
        <div class="alert alert-danger alert-dismissible fade d-none" role="alert" id="errorAlert">

        </div>
        @if (Session::has('success'))
            <div class="alert alert-success" role="alert">
                {!! Session::get('success') !!}
            </div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-danger" role="alert">
                {!! Session::get('error') !!}
            </div>
        @endif
        <div class="top d-flex align-items-center justify-content-between w-100 gap-10">
            <div class="title">
                <h1> Welcome, {{ Auth::user()->name }} -
                    @if (Auth::user()->is_checker)
                        Checker
                    @else
                        Marker
                    @endif
                </h1>
            </div>
            <div class="wallet">
                @if (Auth::user()->is_maker)
                    Wallet Balance: {{ Auth::user()->wallet()->value('balance') }}
                @endif

            </div>
        </div>

        <div class="panel d-flex my-2 flex-column">
            <div class="top d-flex justify-content-between">
                <div class="my-2">
                    <h4>Transactions</h4>
                </div>
                <div class="actions">
                    @if (Auth::user()->is_maker)
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newTrxns">New
                            Transactrion</button>
                    @endif
                </div>
            </div>



            <div class="table">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Transaction Description</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Status</th>

                            <th scope="col">Transaction Type</th>
                            @if (Auth::user()->is_checker)
                                <th scope="col">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <th scope="row">{{ $transaction->id }}</th>
                                <td>{{ $transaction->description }}</td>
                                <td>{{ $transaction->amount }}</td>
                                <td>{{ $transaction->status }}</td>
                                <td>{{ $transaction->transaction_type }}</td>
                                @if (Auth::user()->is_checker)
                                    <td>
                                        @if ($transaction->status == 'pending')
                                            <div class="d-flex gap-3">
                                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#trxnApprove"
                                                    data-transaction-id="{{ $transaction->id }}">Approve</button>

                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#trxnReject"
                                                    data-transaction-id="{{ $transaction->id }}">Reject</button>

                                            </div>
                                        @endif
                                        @if ($transaction->status == 'reject' && Auth::user()->is_checker)
                                            Rejected
                                        @elseif($transaction->status == 'approved' && Auth::user()->is_checker)
                                            Approved
                                        @endif


                                    </td>
                                @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>


    {{-- New Transaction Modal --}}
    <div class="modal fade" tabindex="-1" id="newTrxns" tabindex="-1" aria-labelledby="newTrxnsLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('new_transaction') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="description">Description</label>
                            <input class="form-control" name="description" id="description" />
                        </div>
                        <div class="mb-3">
                            <label for="amount">Amount</label>
                            <input class="form-control" name="amount" id="amount" type="number" />
                        </div>
                        <div class="mb-3">
                            <label for="type">Transaction type</label>
                            <select class="form-control" name="type"id="type">
                                <option value="debit" default>Debit</option>
                                <option value="credit">Credit</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Transaction Reject Modal --}}
    <div class="modal fade" tabindex="-1" id="trxnReject" tabindex="-1" aria-labelledby="trxnRejectLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure to reject transaction?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    <form method="post" id='RejectForm'>
                        @csrf
                        <button type="submit" class="btn btn-primary">Reject</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Transaction Approve Modal --}}
    <div class="modal fade" tabindex="-1" id="trxnApprove" tabindex="-1" aria-labelledby="trxnApproveLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure to approve transaction?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form method="post" id='ApproveForm'>
                        @csrf <button type="submit" class="btn btn-primary">Approve</button> </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Transaction Approve Modal --}}
    <script>
        $(document).ready(function() {
            var transactionId;
            $('#trxnApprove').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                transactionId = button.data('transaction-id');

            });
            $('#trxnReject').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                transactionId = button.data('transaction-id');

            });

            $('#ApproveForm').on('submit', function(e) {
                console.log(transactionId, 'approve')
                e.preventDefault();

                $.ajax({
                    url: `transaction/${transactionId}/approve`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {

                        $('#trxnApprove').modal('hide');
                        $('#successAlert')[0].innerHTML = response.message;
                        $('#successAlert')[0].classList.remove('d-none');
                        $('#successAlert')[0].classList.add('show');
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 3000);
                    },
                    error: function(response) {

                        alert('An error occurred. Please try again.');
                    }
                });
            });
            $('#RejectForm').on('submit', function(e) {
                console.log('rejecting');
                e.preventDefault();
                $.ajax({
                    url: `transaction/${transactionId}/reject`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {

                        $('#trxnReject').modal('hide');
                        $('#errorAlert')[0].innerHTML = response.message;
                        $('#errorAlert')[0].classList.remove('d-none');
                        $('#errorAlert')[0].classList.add('show');
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 3000);

                    },
                    error: function(response) {}
                });
            });
        });
    </script>
@endsection
