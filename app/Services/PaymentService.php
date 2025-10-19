<?php

// ============================================
// SERVICIO DE PAGOS - app/Services/PaymentService.php
// ============================================

namespace App\Services;

use App\Models\Order;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PaymentService
{
    protected $stripeSecretKey;
    protected $paypalClient;

    public function __construct()
    {
        $this->stripeSecretKey = config('services.stripe.secret');
        $this->initializePayPalClient();
    }

    protected function initializePayPalClient()
    {
        $mode = config('services.paypal.mode');
        
        if ($mode === 'live') {
            $environment = new ProductionEnvironment(
                config('services.paypal.live.client_id'),
                config('services.paypal.live.client_secret')
            );
        } else {
            $environment = new SandboxEnvironment(
                config('services.paypal.sandbox.client_id'),
                config('services.paypal.sandbox.client_secret')
            );
        }

        $this->paypalClient = new PayPalHttpClient($environment);
    }

    // ============================================
    // STRIPE
    // ============================================

    public function createStripeCheckoutSession(Order $order)
    {
        Stripe::setApiKey($this->stripeSecretKey);

        $lineItems = [];

        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item->product_name,
                    ],
                    'unit_amount' => (int)($item->price * 100), // Stripe usa centavos
                ],
                'quantity' => $item->quantity,
            ];
        }

        // Añadir descuento si existe
        if ($order->discount > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Descuento (' . $order->coupon_code . ')',
                    ],
                    'unit_amount' => -(int)($order->discount * 100),
                ],
                'quantity' => 1,
            ];
        }

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('payment.success', ['order' => $order->id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel', ['order' => $order->id]),
            'customer_email' => $order->email,
            'metadata' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ],
        ]);

        return $session;
    }

    public function verifyStripePayment($sessionId)
    {
        Stripe::setApiKey($this->stripeSecretKey);
        
        try {
            $session = StripeSession::retrieve($sessionId);
            return [
                'success' => $session->payment_status === 'paid',
                'payment_id' => $session->payment_intent,
                'order_id' => $session->metadata->order_id ?? null,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    // ============================================
    // PAYPAL
    // ============================================

    public function createPayPalOrder(Order $order)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');

        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'name' => $item->product_name,
                'unit_amount' => [
                    'currency_code' => 'EUR',
                    'value' => number_format($item->price, 2, '.', ''),
                ],
                'quantity' => $item->quantity,
            ];
        }

        $body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => $order->order_number,
                'amount' => [
                    'currency_code' => 'EUR',
                    'value' => number_format($order->total, 2, '.', ''),
                    'breakdown' => [
                        'item_total' => [
                            'currency_code' => 'EUR',
                            'value' => number_format($order->subtotal, 2, '.', ''),
                        ],
                        'discount' => [
                            'currency_code' => 'EUR',
                            'value' => number_format($order->discount, 2, '.', ''),
                        ],
                    ],
                ],
                'items' => $items,
            ]],
            'application_context' => [
                'brand_name' => 'TheArtPrints',
                'landing_page' => 'BILLING',
                'user_action' => 'PAY_NOW',
                'return_url' => route('payment.paypal.success', ['order' => $order->id]),
                'cancel_url' => route('payment.cancel', ['order' => $order->id]),
            ],
        ];

        $request->body = $body;

        try {
            $response = $this->paypalClient->execute($request);
            return [
                'success' => true,
                'order_id' => $response->result->id,
                'approval_url' => collect($response->result->links)
                    ->firstWhere('rel', 'approve')
                    ->href ?? null,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function capturePayPalOrder($paypalOrderId)
    {
        $request = new OrdersCaptureRequest($paypalOrderId);

        try {
            $response = $this->paypalClient->execute($request);
            
            return [
                'success' => $response->result->status === 'COMPLETED',
                'payment_id' => $response->result->id,
                'payer_email' => $response->result->payer->email_address ?? null,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}