<?php

// app/Livewire/Shop/ProductList.php
namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;

#[Layout('layouts.app')]
#[Title('Tienda - TheArtPrints')]
class ProductList extends Component
{
    use WithPagination;

    #[Url(as: 'buscar')]
    public $search = '';
    
    #[Url(as: 'categoria')]
    public $categoryFilter = '';
    
    #[Url(as: 'tags')]
    public $selectedTags = [];
    
    #[Url(as: 'orden')]
    public $sortBy = 'newest';
    
    #[Url(as: 'tipo')]
    public $typeFilter = '';

    public $showFilters = false;
    public $priceMin = '';
    public $priceMax = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function toggleTag($tagId)
    {
        if (in_array($tagId, $this->selectedTags)) {
            $this->selectedTags = array_diff($this->selectedTags, [$tagId]);
        } else {
            $this->selectedTags[] = $tagId;
        }
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'categoryFilter', 'selectedTags', 'sortBy', 'typeFilter', 'priceMin', 'priceMax']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::with(['category', 'primaryImage', 'tags'])
            ->where('is_active', true);

        // Búsqueda
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Filtro de categoría
        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        // Filtro de tipo
        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        // Filtro de tags
        if (!empty($this->selectedTags)) {
            $query->whereHas('tags', function ($q) {
                $q->whereIn('tags.id', $this->selectedTags);
            });
        }

        // Filtro de precio
        if ($this->priceMin !== '') {
            $query->where('price', '>=', $this->priceMin);
        }
        if ($this->priceMax !== '') {
            $query->where('price', '<=', $this->priceMax);
        }

        // Ordenamiento
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
            case 'featured':
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12);
        $categories = Category::whereNull('parent_id')->where('is_active', true)->get();
        $tags = Tag::withCount('products')->having('products_count', '>', 0)->get();

        return view('livewire.shop.product-list', [
            'products' => $products,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }
}