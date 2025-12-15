<?php

namespace App\Livewire\Purchase;

use Livewire\Component;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\StockTransaction;

class PurchaseCreate extends Component
{
    public $supplier_id;
    public $purchase_date;
    public $products = [];
    public $total_amount = 0;
    public $grand_total = 0;

    public $allSuppliers = [];
    public $allProducts = [];
    public $supplierProducts = [];

    public function mount()
    {
        $this->allSuppliers = Supplier::where('status', 1)->get();
        $this->allProducts = Product::where('status', 1)->get();
        $this->supplierProducts = $this->allProducts;
        $this->purchase_date = now()->format('Y-m-d');
        $this->addProductRow();
    }

    // Add a new product row
    public function addProductRow()
    {
        $this->products[] = [
            'product_id' => '',
            'expiry_date' => null,
            'quantity' => 1,
            'weight' => 0,
            'weight_unit' => 'gm',
            'unit_price' => 0,
            'subtotal' => 0
        ];
    }

    // Remove product row
    public function removeProductRow($index)
    {
        unset($this->products[$index]);
        $this->products = array_values($this->products);
        $this->calculateTotal();
    }

    // Update total when products change
    public function updatedProducts($value, $name)
    {
        $this->calculateTotal();
    }

    // Filter products when supplier changes
    public function updatedSupplierId($value)
    {
        if ($value) {
            $this->supplierProducts = Product::where('status', 1)
                ->where('supplier_id', $value) // make sure Product has supplier_id
                ->get();
        } else {
            $this->supplierProducts = $this->allProducts;
        }

        // Reset product selection
        foreach ($this->products as $key => $p) {
            $this->products[$key]['product_id'] = '';
        }
    }

    // Calculate subtotal and total
    public function calculateTotal()
    {
        $total = 0;

        foreach ($this->products as $key => $p) {
            // Only calculate if product_id is selected
            if (!empty($p['product_id'])) {
                $quantity = floatval($p['quantity']);
                $unit_price = floatval($p['unit_price']);
                $subtotal = round($quantity * $unit_price, 2);
                $this->products[$key]['subtotal'] = $subtotal;
                $total += $subtotal;
            } else {
                $this->products[$key]['subtotal'] = 0;
            }
        }

        $this->total_amount = $total;
        $this->grand_total = $total; // apply discount/tax if needed
    }


    // Save purchase
    public function save()
    {
        $this->validate([
            'supplier_id' => 'required',
            'purchase_date' => 'required|date',
            'products.*.product_id' => 'required',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.weight' => 'nullable|numeric|min:0',
            'products.*.weight_unit' => 'nullable|in:gm,ml',
            'products.*.unit_price' => 'required|numeric|min:0',
            'products.*.expiry_date' => 'nullable|date|after:today',
        ]);

        DB::transaction(function() {
            $purchase = Purchase::create([
                'invoice_no' => Str::upper('PUR'.time()),
                'supplier_id' => $this->supplier_id,
                'user_id' => Auth::id(),
                'total_amount' => $this->total_amount,
                'grand_total' => $this->grand_total,
                'purchase_date' => $this->purchase_date,
            ]);

            foreach ($this->products as $p) {
                $item = PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $p['product_id'],
                    'quantity' => $p['quantity'],
                    'unit_price' => $p['unit_price'],
                    'weight' => $p['weight'],
                    'weight_unit' => $p['weight_unit'],
                    'subtotal' => $p['subtotal'],
                    'expiry_date' => $p['expiry_date'],
                ]);

                // Increment stock
                $product = $item->product;
                if ($product->track_inventory) {
                    $product->increment('stock_qty', $p['quantity']);

                    StockTransaction::create([
                        'product_id' => $product->id,
                        'qty_change' => $p['quantity'],
                        'type' => 'purchase',
                        'reference_id' => $purchase->id,
                        'user_id' => Auth::id(),
                    ]);
                }
            }
        });

        session()->flash('success', 'Purchase created successfully.');
        return redirect()->route('purchases.index');
    }

    public function render()
    {
        return view('livewire.purchase.purchase-create');
    }
}
