<?php
namespace App\Livewire\Sales;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleCreate extends Component
{
    public $customer_id;
    public $date;
    public $discount = 0;
    public $shipping = 0;
    public $paid = 0;
    public $grand_total = 0;

    public $due = 0;

    public $invoice_number;
    public $product_id;
    public $unit_price;
    public $payment_status = 'due';

    public $products = [];

    public function mount()
    {
        $this->date = date('Y-m-d');
        $this->addProductRow();
        $this->generateInvoiceNumber();
    }
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


    public function generateInvoiceNumber()
    {
        $nextId = (Sale::max('id') ?? 0) + 1;
        $cust = $this->customer_id ?: 1;
        $date = date('Ymd');

        $this->invoice_number = "MM-{$cust}-{$nextId}-{$date}";
    }

    public function updatedCustomerId()
    {
        $this->generateInvoiceNumber();
    }


    public function updatedDiscount()
    {
        $this->calculateTotals();
    }

    public function updatedPaid()
    {
        $this->calculateTotals();
    }

    public function addProductRow()
    {
        $this->products[] = [
            'product_id' => '',
            'qty' => 1,
            'unit_price' => 0,
            'subtotal' => 0
        ];
    }

    public function removeProductRow($index)
    {
        unset($this->products[$index]);
        $this->products = array_values($this->products);
    }

    public function updatedProducts()
    {

        $this->calculateTotals();
    }


    public function calculateTotals()
    {
        $grandTotal = 0;

        foreach ($this->products as $index => $item) {

            // Convert values safely
            $qty   = (float) ($item['qty'] ?? 0);
            $price = (float) ($item['unit_price'] ?? 0);

            $this->products[$index]['subtotal'] = $qty * $price;

            $grandTotal += $this->products[$index]['subtotal'];
        }

        // Discount & Paid: force to float
        $discount = (float) ($this->discount ?? 0);
        $paid     = (float) ($this->paid ?? 0);

        $this->grand_total = $grandTotal - $discount;

        // Prevent negative total
        if ($this->grand_total < 0) {
            $this->grand_total = 0;
        }

        // Calculate due
        $this->due = $this->grand_total - $paid;

        if ($this->due < 0) {
            $this->due = 0;
        }

        // Auto update payment status
        if ($paid <= 0) {
            $this->payment_status = 'due';
        } elseif ($paid < $this->grand_total) {
            $this->payment_status = 'partial';
        } else {
            $this->payment_status = 'paid';
        }
    }





    public function save()
    {
        DB::beginTransaction();

        try {
            // Create sale
            $sale = Sale::create([
                'customer_id' => $this->customer_id,
                'user_id' => Auth::id(),
                'sale_date' => $this->date,
                'discount' => $this->discount ?? 0,
                'shipping' => $this->shipping,
                'paid' => $this->paid ?? 0,
                'due' => $this->due ?? 0,
                'grand_total' => $this->grand_total,
                'total_amount' => $this->grand_total + $this->discount,
                'payment_status' => $this->paid >= $this->grand_total ? 'paid' :
                                    ($this->paid > 0 ? 'partial' : 'due'),
            ]);

            // Create sale items
            foreach ($this->products as $p) {
                if (!$p['product_id']) continue;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $p['product_id'],
                    'qty' => $p['qty'],
                    'unit_price' => $p['unit_price'],
                    'subtotal' => $p['subtotal'],
                ]);

                Product::where('id', $p['product_id'])->decrement('stock_qty', $p['qty']);
            }

            // Create invoice
            $invoice = Invoice::create([
                'sale_id' => $sale->id,
                'customer_id' => $this->customer_id,
            ]);
            
            // Link invoice to sale
            $sale->invoice_id = $invoice->id;
            $sale->save();


            DB::commit();

            session()->flash('success', 'Sale Created Successfully!');
            return redirect()->route('sales.index');

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.sales.sale-create', [
            'allCustomers' => Customer::all(),
            'allProducts' => Product::all()
        ]);
    }
}
