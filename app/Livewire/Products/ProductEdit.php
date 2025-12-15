<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Str;

class ProductEdit extends Component
{
    public Product $product;

    public $category_id;
    public $supplier_id;
    public $name;
    public $skuPreview;
    public $barcodePreview;
    public $unit;
    public $purchase_price;
    public $sell_price;
    public $stock_qty;
    public $status;

    public function mount(Product $product)
    {
        $this->product = $product;

        // Fill form fields
        $this->category_id = $product->category_id;
        $this->supplier_id = $product->supplier_id;
        $this->name = $product->name;
        $this->skuPreview = $product->sku;
        $this->barcodePreview = $product->barcode;
        $this->unit = $product->unit;
        $this->purchase_price = $product->purchase_price;
        $this->sell_price = $product->sell_price;
        $this->stock_qty = $product->stock_qty;
        $this->status = $product->status;
    }

    public function updatedCategoryId()
    {
        $this->generateSku();
    }

    public function updatedsupplier_id()
    {
        $this->generateSku();
    }

    public function updatedName()
    {
        $this->generateSku();
    }

    private function generateSku()
    {
        $category = Category::find($this->category_id);
        $supplier = Supplier::find($this->supplier_id);

        $categoryCode = $category ? strtoupper(substr($category->name, 0, 3)) : 'PRD';
        $supplierCode = $supplier ? strtoupper(substr($supplier->name, 0, 3)) : 'SUP';
        logger('Category changed: ' . $this->product);
        $this->skuPreview = $categoryCode . '-' . $supplierCode . '-' . str_pad($this->product->id, 4, '0', STR_PAD_LEFT);

        $prefix = 'MM';
        $this->barcodePreview = $prefix . str_pad($this->product->id, 10, '0', STR_PAD_LEFT);
    }

    public function save()
    {
        $this->validate([
            'category_id' => 'required',
            'supplier_id' => 'required',
            'name' => 'required',
            'purchase_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
            'stock_qty' => 'required|numeric',
        ]);

        $this->product->update([
            'category_id' => $this->category_id,
            'supplier_id' => $this->supplier_id,
            'name' => $this->name,
            'sku' => $this->skuPreview,
            'barcode' => $this->barcodePreview,
            'unit' => $this->unit,
            'purchase_price' => $this->purchase_price,
            'sell_price' => $this->sell_price,
            'stock_qty' => $this->stock_qty,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Product updated successfully.');

        return redirect()->route('products.index');
    }

    public function render()
    {
        $categories = Category::where('status',1)->get();
        $suppliers = Supplier::where('status',1)->get();

        return view('livewire.products.product-edit', compact('categories','suppliers'));
    }
}
