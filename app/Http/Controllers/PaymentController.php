<?php

// ============================================
// CONTROLADOR DE PAGOS - app/Http/Controllers/PaymentController.php
// ============================================

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function success(Request $request, Order $order)
    {
        // Verificar que el usuario tiene acceso a esta orden
        if (!Auth::check() || Auth::id() !== $order->user_id) {
            abort(403);
        }

        if ($order->payment_method === 'stripe') {
            $sessionId = $request->get('session_id');
            
            if (!$sessionId) {
                return redirect()->route('shop.cart')->with('error', 'Sesión de pago no válida');
            }

            $result = $this->paymentService->verifyStripePayment($sessionId);

            if ($result['success']) {
                $order->update([
                    'payment_status' => 'paid',
                    'payment_id' => $result['payment_id'],
                    'status' => 'processing',
                    'paid_at' => now(),
                ]);

                return redirect()->route('order.confirmation', $order->order_number);
            }
        }

        return redirect()->route('shop.cart')->with('error', 'Error al verificar el pago');
    }

    public function paypalSuccess(Request $request, Order $order)
    {
        // Verificar que el usuario tiene acceso a esta orden
        if (!Auth::check() || Auth::id() !== $order->user_id) {
            abort(403);
        }

        $paypalOrderId = $request->get('token');

        if (!$paypalOrderId) {
            return redirect()->route('shop.cart')->with('error', 'Pago de PayPal no válido');
        }

        $result = $this->paymentService->capturePayPalOrder($paypalOrderId);

        if ($result['success']) {
            $order->update([
                'payment_status' => 'paid',
                'payment_id' => $result['payment_id'],
                'status' => 'processing',
                'paid_at' => now(),
            ]);

            return redirect()->route('order.confirmation', $order->order_number);
        }

        return redirect()->route('shop.cart')->with('error', 'Error al capturar el pago de PayPal');
    }

    public function cancel(Order $order)
    {
        if (!Auth::check() || Auth::id() !== $order->user_id) {
            abort(403);
        }

        // Restaurar stock si se canceló antes de pagar
        foreach ($order->items as $item) {
            if ($item->product->isPhysical() && $item->product->track_inventory) {
                if ($item->variant) {
                    $item->variant->increment('stock', $item->quantity);
                } else {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        }

        $order->update([
            'status' => 'cancelled',
            'payment_status' => 'failed',
        ]);

        return redirect()->route('shop.cart')->with('error', 'Pago cancelado');
    }

    // Webhook de Stripe
    public function stripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);

            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;
                
                $orderId = $session->metadata->order_id ?? null;
                
                if ($orderId) {
                    $order = Order::find($orderId);
                    
                    if ($order && $order->payment_status !== 'paid') {
                        $order->update([
                            'payment_status' => 'paid',
                            'status' => 'processing',
                            'paid_at' => now(),
                        ]);
                    }
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}