<?php

// app/Livewire/Admin/Orders/OrderShow.php
namespace App\Livewire\Admin\Orders;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Order;

#[Layout('layouts.admin')]
class OrderShow extends Component
{
    public Order $order;

    public function mount($id)
    {
        $this->order = Order::with(['user', 'items.product', 'coupon'])->findOrFail($id);
    }

    public function updateStatus($status)
    {
        $this->order->update(['status' => $status]);
        session()->flash('message', 'Estado actualizado');
    }

    public function updatePaymentStatus($status)
    {
        $this->order->update(['payment_status' => $status]);
        
        if ($status === 'paid') {
            $this->order->markAsPaid();
        }
        
        session()->flash('message', 'Estado de pago actualizado');
    }

    public function render()
    {
        return view('livewire.admin.orders.order-show')
            ->title('Pedido ' . $this->order->order_number . ' - Admin');
    }
}