<?php

namespace App\Livewire\Sales;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleEdit extends Component
{
    public Sale $sale;

    public $customer_id;
    public $date;
    public $discount = 0;
    public $shipping = 0;
    public $paid = 0;
    public $grand_total = 0;
    public $due = 0;
    public $payment_status = 'due';
    public $invoice_number;
    public $search = [];
    public $showDropdown = [];
    public $products = [];

    protected $listeners = ['closeAllProductDropdowns'];
    /** -------------------------
     *  MOUNT
     * ------------------------*/
    public function mount(Sale $sale)
    {
        $this->sale = $sale->load('items');

        $this->customer_id    = $sale->customer_id;
        $this->date           = $sale->sale_date;
        $this->discount       = $sale->discount;
        $this->shipping       = $sale->shipping;
        $this->paid           = $sale->paid;
        $this->grand_total    = $sale->grand_total;
        $this->due            = $sale->due;
        $this->payment_status = $sale->payment_status;
        $this->invoice_number = $sale->invoice?->invoice_number;

        foreach ($sale->items as $index => $item) {
            $product = Product::find($item->product_id);
            $this->products[] = [
                'product_id' => $item->product_id,
                'qty'        => $item->qty,
                'unit_price' => $item->unit_price,
                'subtotal'   => $item->subtotal,
            ];

            $this->search[$index] = $product?->name ?? '';
            $this->showDropdown[$index] = false;
        }

        $this->calculateTotals();
    }

public function closeAllProductDropdowns()
    {
        $this->showDropdown = [];
    }
    /** -------------------------
     *  PRODUCT PRICE
     * ------------------------*/
    public function loadProductPrice($index)
    {
        $productId = $this->products[$index]['product_id'];

        if (!$productId) {
            $this->products[$index]['unit_price'] = 0;
            return;
        }

        $product = Product::find($productId);
        $this->products[$index]['unit_price'] = $product->sell_price ?? 0;

        $this->calculateTotals();
    }

    /** -------------------------
     *  ROW HANDLING
     * ------------------------*/
    public function addProductRow()
    {
        $this->products[] = [
            'product_id' => '',
            'qty'        => 1,
            'unit_price' => 0,
            'subtotal'   => 0
        ];
    }

    public function selectProduct($index, $productId)
{
    $product = Product::find($productId);
    if (!$product) return;

    $this->products[$index]['product_id']   = $product->id;
    $this->products[$index]['product_name'] = $product->name;
    $this->products[$index]['unit_price']   = $product->sell_price ?? 0;

    $this->search[$index] = $product->name;
    $this->showDropdown[$index] = false;

    $this->calculateTotals();
}
    public function removeProductRow($index)
    {
        unset($this->products[$index]);
        $this->products = array_values($this->products);
        $this->calculateTotals();
    }

    public function updatedProducts()
    {
        $this->calculateTotals();
    }

    public function updatedDiscount()
    {
        $this->calculateTotals();
    }

    public function updatedPaid()
    {
        $this->calculateTotals();
    }

    /** -------------------------
     *  TOTAL CALCULATION
     * ------------------------*/
    public function calculateTotals()
    {
        $total = 0;

        foreach ($this->products as $i => $item) {
            $qty   = (float) ($item['qty'] ?? 0);
            $price = (float) ($item['unit_price'] ?? 0);

            $this->products[$i]['subtotal'] = $qty * $price;
            $total += $this->products[$i]['subtotal'];
        }

        $discount = (float) $this->discount ?? 0;
        $paid     = (float) $this->paid ?? 0;

        $this->grand_total = max(0, $total - $discount);
        $this->due = max(0, $this->grand_total - $paid);

        if ($paid <= 0) {
            $this->payment_status = 'due';
        } elseif ($paid < $this->grand_total) {
            $this->payment_status = 'partial';
        } else {
            $this->payment_status = 'paid';
        }
    }

    /** -------------------------
     *  UPDATE SALE
     * ------------------------*/
    public function update()
    {
        DB::beginTransaction();

        try {
            /** 🔁 Reverse previous stock */
            foreach ($this->sale->items as $oldItem) {
                Product::where('id', $oldItem->product_id)
                    ->increment('stock_qty', $oldItem->qty);
            }

            /** 🗑 Remove old items */
            SaleItem::where('sale_id', $this->sale->id)->delete();

            /** ✏ Update Sale */
            $this->sale->update([
                'customer_id'    => $this->customer_id,
                'sale_date'      => $this->date,
                'discount'       => $this->discount ?? 0,
                'shipping'       => $this->shipping,
                'paid'           => $this->paid ?? 0,
                'due'            => $this->due ?? 0,
                'grand_total'    => $this->grand_total,
                'total_amount'   => $this->grand_total + ($this->discount ?? 0),
                'payment_status' => $this->payment_status,
                'user_id'        => Auth::id(),
            ]);

            /** ➕ Insert new items & update stock */
            foreach ($this->products as $p) {
                if (!$p['product_id']) continue;

                SaleItem::create([
                    'sale_id'    => $this->sale->id,
                    'product_id' => $p['product_id'],
                    'qty'        => $p['qty'],
                    'unit_price' => $p['unit_price'],
                    'subtotal'   => $p['subtotal'],
                ]);

                Product::where('id', $p['product_id'])
                    ->decrement('stock_qty', $p['qty']);
            }

            DB::commit();

            session()->flash('success', 'Sale Updated Successfully!');
            return redirect()->route('sales.index');

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.sales.sale-edit', [
            'allCustomers' => Customer::all(),
            'allProducts'  => Product::all(),
        ]);
    }
}
