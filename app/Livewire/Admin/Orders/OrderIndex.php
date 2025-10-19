<?php

// app/Livewire/Admin/Orders/OrderIndex.php
namespace App\Livewire\Admin\Orders;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use App\Models\Order;

#[Layout('layouts.admin')]
#[Title('Pedidos - Admin')]
class OrderIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterPaymentStatus = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updateStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => $status]);
        
        session()->flash('message', 'Estado actualizado');
    }

    public function render()
    {
        $query = Order::with('user');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterPaymentStatus) {
            $query->where('payment_status', $this->filterPaymentStatus);
        }

        $orders = $query->latest()->paginate(20);

        return view('livewire.admin.orders.order-index', compact('orders'));
    }
}