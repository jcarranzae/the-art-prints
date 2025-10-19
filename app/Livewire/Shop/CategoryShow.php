<?php

// app/Livewire/Shop/CategoryShow.php
namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Category;
use App\Models\Product;

#[Layout('layouts.app')]
class CategoryShow extends Component
{
    use WithPagination;

    public $categorySlug;
    public $sortBy = 'newest';

    public function mount($slug)
    {
        $this->categorySlug = $slug;
    }

    public function render()
    {
        $category = Category::with('children')
            ->where('slug', $this->categorySlug)
            ->where('is_active', true)
            ->firstOrFail();

        $query = Product::with(['primaryImage', 'tags'])
            ->where('category_id', $category->id)
            ->where('is_active', true);

        switch ($this->sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('sales_count', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12);

        return view('livewire.shop.category-show', [
            'category' => $category,
            'products' => $products,
        ])->title($category->name . ' - TheArtPrints');
    }
}