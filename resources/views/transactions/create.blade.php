@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Submit a New Transaction') }}</div>

                <div class="card-body">
                    <form action="/transactions" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="type" class="form-label">Type:</label>
                            <select name="type" id="type" class="form-select">
                                <option value="credit">Credit</option>
                                <option value="debit">Debit</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount:</label>
                            <input type="text" name="amount" id="amount" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description:</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Transaction</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
