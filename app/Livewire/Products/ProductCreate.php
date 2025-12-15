<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Str;

class ProductCreate extends Component
{
    protected string $layout = 'layouts.app';
    public $category_id, $supplier_id, $name, $sku, $unit = 'pcs';
    public $purchase_price, $sell_price, $stock = 0, $status = 1;
    public $skuPreview;
    public $barcodePreview;

    // Called automatically when category_id changes
    public function updatedCategoryId()
    {
        logger()->info("category_id");
        $this->generateSkuAndBarcode();
    }

    // Called automatically when name changes
    public function updatedName()
    {
        $this->generateSkuAndBarcode();
    }

    public function updatedSupplierId()
    {
        logger()->info("supplier_id");
        $this->generateSkuAndBarcode();
    }

    private function generateSkuAndBarcode()
    {
        $category = Category::find($this->category_id);
        $supplier = Supplier::find($this->supplier_id);

        $categoryCode = $category ? strtoupper(substr($category->name, 0, 3)) : 'PRD';
        $supplierCode = $supplier ? strtoupper(substr($supplier->name, 0, 3)) : 'SUP';

        $nextId = (Product::max('id') ?? 0) + 1;
        $this->skuPreview = $categoryCode . '-' . $supplierCode . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $prefix = 'MM'; // barcode prefix
        $this->barcodePreview = $prefix . str_pad($nextId, 10, '0', STR_PAD_LEFT);

        logger()->info("Generated SKU: {$this->skuPreview}, Barcode: {$this->barcodePreview}");
    }

    public function save()
    {
        $this->validate([
            'category_id' => 'required',
            'name' => 'required|unique:products,name',
            'sell_price' => 'required|numeric',
            'purchase_price' => 'required|numeric',
            'stock' => 'required|numeric',
            'supplier_id' => 'required',
        ]);

        $product = Product::create([
            'category_id' => $this->category_id,
            'supplier_id' => $this->supplier_id,
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'sku' => $this->skuPreview,
            'barcode' => $this->barcodePreview,
            'unit' => $this->unit,
            'purchase_price' => $this->purchase_price,
            'sell_price' => $this->sell_price,
            'stock_qty' => $this->stock,
            'status' => $this->status
        ]);

        // $product->suppliers()->sync($this->supplier_id);
        
        /*
        $data = [];
        foreach($this->supplier_ids as $supplierId) {
            $data[$supplierId] = [
                'purchase_price' => $this->supplierPrices[$supplierId] ?? $this->purchase_price
            ];
        }

        $product->suppliers()->sync($data);
        */

        session()->flash('success', 'Product added successfully.');
        return redirect()->route('products.index');
    }


    public function render()
    {   $suppliers = Supplier::where('status',1)->get();
        $categories = Category::where('status', 1)->get();
        return view('livewire.products.product-create', compact('categories', 'suppliers'));
    }
}
