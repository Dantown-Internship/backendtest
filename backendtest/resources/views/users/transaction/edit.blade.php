<x-app-layout>


    <x-slot name="header">
        <div class="container mb-5 py-2">
            <div class="row">
                <div class="col-6">
                    <h4>Modify Transactions</h4>
                </div>
                <div class="col-6 text-end">
                    <a href="{{ route('dashboard') }}" class="btn btn-info">Back</a>
                </div>
            </div>
        </div>
    </x-slot>


    <div class="container">
        <div class="row">
            <div class="col-12">

                <form action="{{ route('transaction.update', $transaction->id) }}" method="post" class="form">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label for="type">Type:</label>
                        <select name="type" id="type" class="form-control">
                            <option value="credit" {{ ($transaction->type=='credit')?'selected':''; }}>Credit</option>
                            <option value="debit" {{ ($transaction->type=='debit')?'selected':''; }}>Debit</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="number" name="amount" value="{{ $transaction->amount }}" id="amount" step="0.1" required class="form-control">
                    </div>


                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" class="form-control">{{ $transaction->description }}</textarea>
                    </div>


                    <button type="submit" class="btn btn-sm btn-success d-block form-control mt-2">Update</button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
