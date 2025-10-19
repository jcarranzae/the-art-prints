<?php

// app/Livewire/Admin/Products/ProductIndex.php
namespace App\Livewire\Admin\Products;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use App\Models\Product;

#[Layout('layouts.admin')]
#[Title('Productos - Admin')]
class ProductIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterType = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteProduct($productId)
    {
        $product = Product::findOrFail($productId);
        $product->delete();
        
        session()->flash('message', 'Producto eliminado correctamente');
    }

    public function toggleStatus($productId)
    {
        $product = Product::findOrFail($productId);
        $product->update(['is_active' => !$product->is_active]);
        
        session()->flash('message', 'Estado actualizado');
    }

    public function render()
    {
        $query = Product::with(['category', 'primaryImage']);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->filterStatus !== '') {
            $query->where('is_active', $this->filterStatus);
        }

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        $products = $query->latest()->paginate(20);

        return view('livewire.admin.products.product-index', compact('products'));
    }
}