<?php

namespace App\Http\Livewire\Pos;
use Livewire\Component;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use DB;

class PosTerminal extends Component
{
    public $cart = []; // [product_id => ['qty'=>1, 'unit_price'=>..]]
    public $customer_id;
    public $payment_method = 'cash';
    public $discount = 0;

    public function addProduct($productId){
        $product = Product::find($productId);
        if(!$product) return;
        if(isset($this->cart[$productId])) $this->cart[$productId]['qty']++;
        else $this->cart[$productId] = ['qty'=>1, 'unit_price'=>$product->sell_price, 'name'=>$product->name];
        $this->emit('cart-updated');
    }

    public function updateQty($productId, $qty){
        if($qty <= 0){ unset($this->cart[$productId]); return; }
        $this->cart[$productId]['qty'] = (int)$qty;
    }

    public function checkout(){
        DB::transaction(function() {
            $total = 0;
            foreach($this->cart as $p) $total += $p['qty'] * $p['unit_price'];
            $sale = Sale::create([
              'invoice_no' => 'INV'.time(),
              'customer_id' => $this->customer_id,
              'user_id' => auth()->id(),
              'total_amount' => $total,
              'discount' => $this->discount,
              'tax' => 0,
              'shipping' => 0,
              'grand_total' => $total - $this->discount,
              'status' => 'paid',
            ]);
            foreach($this->cart as $productId => $p){
                $subtotal = $p['qty'] * $p['unit_price'];
                $sale->items()->create([
                    'product_id'=>$productId,
                    'qty'=>$p['qty'],
                    'unit_price'=>$p['unit_price'],
                    'discount'=>0,
                    'tax'=>0,
                    'subtotal'=>$subtotal
                ]);
                // adjust stock
                $product = Product::find($productId);
                if($product->track_inventory){
                    $product->decrement('stock_qty', $p['qty']);
                    $product->stockTransactions()->create([
                        'qty_change' => -$p['qty'],
                        'type' => 'sale',
                        'reference_id' => $sale->id,
                        'user_id' => auth()->id(),
                    ]);
                }
            }
            // create payment record etc...
        });

        $this->cart = [];
        session()->flash('success','Sale recorded.');
        return redirect()->route('sales.show', $sale->id);
    }

    public function render(){ return view('livewire.pos.pos-terminal'); }
}

