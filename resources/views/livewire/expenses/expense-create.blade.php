<div class="container mt-3">
    <h3>Add Expense</h3>

    <form wire:submit.prevent="save">
        <div class="row">
            <div class="col-md-4">
                <label>Title</label>
                <input type="text" list="Product" id="product-name"  onchange="fillAmount(event)" wire:model="title" class="form-control">

                <datalist id="Product">
                    @foreach($products as $product)
                        <option value="{{ $product->name }}" data-amount="{{ $product->purchase_price }}"></option>
                    @endforeach
                </datalist>
            </div>

            <div class="col-md-4">
                <label>Category</label>
                <select wire:model="expense_category_id" class="form-select">
                    <option value="">Select</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Date</label>
                <input type="date" wire:model="expense_date" class="form-control">
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-4">
                <label>Amount</label>
                <input type="number" id="amount" wire:model="amount" class="form-control">
            </div>

            <div class="col-md-4">
                <label>Payment Method</label>
                <select wire:model="payment_method" class="form-select">
                    <option value="cash">Cash</option>
                    <option value="bank">Bank</option>
                    <option value="mobile">Mobile</option>
                </select>
            </div>

            <div class="col-md-4">
                <label>Reference</label>
                <input type="text" wire:model="reference" class="form-control">
            </div>
        </div>

        <div class="mt-2">
            <label>Note</label>
            <textarea wire:model="note" class="form-control"></textarea>
        </div>

        <button class="btn btn-success mt-3">Save</button>
    </form>

    @script
<script>
            // Make the function accessible globally via the window object
            window.fillAmount = function(event) {
                const inputElement = event.target;
                const amountInput = document.getElementById('amount');
                // Use querySelector to find the option that matches the current input value
                const selectedOption = document.querySelector('#Product option[value="' + inputElement.value + '"]');

                if (selectedOption) {
                    // Get the data attribute value using the dataset API (modern) or getAttribute (older browsers)
                    amountInput.value = selectedOption.dataset.amount || selectedOption.getAttribute('data-amount');
                } else {
                    // Clear the amount if the input value doesn't match a valid option
                    amountInput.value = '';
                }
            }
        </script>
@endscript
</div>

