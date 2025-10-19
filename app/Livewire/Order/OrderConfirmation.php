<?php

// app/Livewire/Order/OrderConfirmation.php
namespace App\Livewire\Order;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Order;

#[Layout('layouts.app')]
class OrderConfirmation extends Component
{
    public Order $order;

    public function mount($orderNumber)
    {
        $this->order = Order::with(['items.product', 'user'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Verificar que el usuario tenga acceso a esta orden
        if (!auth()->check() || auth()->id() !== $this->order->user_id) {
            abort(403);
        }
    }

    public function render()
    {
        return view('livewire.order.order-confirmation')
            ->title('Confirmación de Pedido - TheArtPrints');
    }
}
