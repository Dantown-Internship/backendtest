<x-app-layout>


    <x-slot name="header">
        <div class="container mb-5 py-2">
            <div class="row">
                <div class="col-6">
                    <h4>Create Transactions</h4>
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

                <form action="{{ route('transaction.store') }}" method="post" class="form">
                    @csrf
                    <div class="form-group">
                        <label for="type">Type:</label>
                        <select name="type" id="type" class="form-control">
                            <option value="credit">Credit</option>
                            <option value="debit">Debit</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="number" name="amount" placeholder="Amount" id="amount" step="0.1" required class="form-control">
                    </div>


                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea name="description"  placeholder="Description" id="description" class="form-control"></textarea>
                    </div>


                    <button type="submit" class="btn btn-sm btn-success d-block form-control mt-2">Create</button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
