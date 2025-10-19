<?php

// app/Livewire/Admin/Dashboard.php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

#[Layout('layouts.admin')]
#[Title('Dashboard - Admin')]
class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total'),
            'total_customers' => User::where('is_admin', false)->count(),
        ];

        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        $topProducts = Product::orderBy('sales_count', 'desc')
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', compact('stats', 'recentOrders', 'topProducts'));
    }
}

