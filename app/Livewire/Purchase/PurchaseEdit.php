<?php

namespace App\Livewire\Purchase;

use Livewire\Component;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseEdit extends Component
{
    public $purchase_id;
    public $supplier_id;
    public $purchase_date;
    public $total_amount = 0;
    public $grand_total = 0;

    public $products = [];   // rows
    public $allProducts = [];
    public $allSuppliers = [];

    public function mount($purchase)
    {
        $purchase = Purchase::with('items')->findOrFail($purchase);

        $this->purchase_id = $purchase->id;
        $this->supplier_id = $purchase->supplier_id;
        $this->purchase_date = $purchase->purchase_date;
        $this->total_amount = $purchase->total_amount;
        $this->grand_total = $purchase->grand_total;

        $this->allProducts = Product::where('status',1)->get();
        $this->allSuppliers = Supplier::where('status',1)->get();
        
        // Load product items
        foreach ($purchase->items as $item) {
            $this->products[] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'weight' => $item->weight,  // ← weight support
                'weight_unit' => $item->weight_unit,
                'unit_price' => $item->unit_price,
                'subtotal' => $item->subtotal,
                'expiry_date' => $item->expiry_date,
            ];
        }

        $this->calculateTotal();
    }

    public function addProductRow()
    {
        $this->products[] = [
            'id' => null,
            'product_id' => '',
            'quantity' => 1,
            'weight' => 0,
            'weight_unit' => 'gm',
            'unit_price' => 0,
            'subtotal' => 0,
            'expiry_date' => null,
        ];
    }

    public function removeProductRow($index)
    {
        unset($this->products[$index]);
        $this->products = array_values($this->products);
        $this->calculateTotal();
    }

    public function updatedProducts()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $total = 0;

        foreach ($this->products as $key => $p) {
            if (!empty($p['product_id'])) {
                $qty = $p['quantity'] ?? 1;
                $price = $p['unit_price'] ?? 0;

                $this->products[$key]['subtotal'] = $qty * $price;
                $total += $this->products[$key]['subtotal'];
            }
        }

        $this->total_amount = $total;
        $this->grand_total = $total;
    }

    public function save()
    {
        // dd($this->products);
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

            /** Fetch purchase */
            $purchase = Purchase::with('items')->find($this->purchase_id);

            /** Update purchase fields */
            $purchase->update([
                'supplier_id' => $this->supplier_id,
                'purchase_date' => $this->purchase_date,
                'total_amount' => $this->total_amount,
                'grand_total' => $this->grand_total,
            ]);

            /** Existing DB items */
            $existingItems = $purchase->items->keyBy('id'); // indexed by id
            $newIDs = [];

            foreach ($this->products as $p) {

                // Check if updating existing item or creating new
                if ($p['id']) {
                    /** Update existing item */
                    $item = PurchaseItem::find($p['id']);

                    $oldQty = $item->quantity;
                    $newQty = $p['quantity'];
                    $diff = $newQty - $oldQty;

                    /** Update item */
                    $item->update([
                        'product_id' => $p['product_id'],
                        'quantity' => $p['quantity'],
                        'weight' => $p['weight'],
                        'weight_unit' => $p['weight_unit'],
                        'unit_price' => $p['unit_price'],
                        'subtotal' => $p['subtotal'],
                        'expiry_date' => $p['expiry_date'],
                    ]);

                    /** Stock adjustment */
                    if ($item->product->track_inventory && $diff != 0) {

                        // Apply difference to stock
                        $item->product->increment('stock_qty', $diff);

                        // Log stock transaction
                        StockTransaction::create([
                            'product_id' => $item->product_id,
                            'qty_change' => $diff,
                            'type' => 'purchase-edit',
                            'reference_id' => $purchase->id,
                            'user_id' => Auth::id(),
                            'note' => "Edited purchase item (was $oldQty, now $newQty)",
                        ]);
                    }

                } else {
                    /** Create new purchase item */
                    $item = PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'product_id' => $p['product_id'],
                        'quantity' => $p['quantity'],
                        'weight' => $p['weight'],
                        'weight_unit' => $p['weight_unit'],
                        'unit_price' => $p['unit_price'],
                        'subtotal' => $p['subtotal'],
                        'expiry_date' => $p['expiry_date'],
                    ]);

                    $newQty = $p['quantity'];

                    /** Increase stock for newly added items */
                    $product = $item->product;
                    if ($product->track_inventory) {

                        $product->increment('stock_qty', $newQty);

                        StockTransaction::create([
                            'product_id' => $product->id,
                            'qty_change' => $newQty,
                            'type' => 'purchase-add',
                            'reference_id' => $purchase->id,
                            'user_id' => Auth::id(),
                            'note' => "Added new purchase item",
                        ]);
                    }
                }

                $newIDs[] = $item->id;
            }

            /** Detect deleted rows */
            $deletedIDs = array_diff($existingItems->keys()->toArray(), $newIDs);

            foreach ($deletedIDs as $deleteID) {

                $oldItem = $existingItems[$deleteID];

                /** Reverse stock for deleted item */
                if ($oldItem->product->track_inventory) {

                    $oldItem->product->decrement('stock_qty', $oldItem->quantity);

                    StockTransaction::create([
                        'product_id' => $oldItem->product_id,
                        'qty_change' => -$oldItem->quantity,
                        'type' => 'purchase-delete',
                        'reference_id' => $purchase->id,
                        'user_id' => Auth::id(),
                        'note' => "Removed purchase item",
                    ]);
                }

                /** Delete item */
                $oldItem->delete();
            }

        });

        session()->flash('success', 'Purchase updated successfully with stock adjustments.');
        return redirect()->route('purchases.index');
    }


    public function render()
    {
        return view('livewire.purchase.purchase-edit');
    }
}
