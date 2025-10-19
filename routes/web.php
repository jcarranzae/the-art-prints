<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Livewire\Shop\ProductList;
use App\Livewire\Shop\ProductShow;
use App\Livewire\Shop\CategoryShow;
use App\Livewire\Cart\CartPage;
use App\Livewire\Checkout\CheckoutPage;
use App\Livewire\Order\OrderConfirmation;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Products\ProductIndex;
use App\Livewire\Admin\Products\ProductCreate;
use App\Livewire\Admin\Products\ProductEdit;
use App\Livewire\Admin\Orders\OrderIndex;
use App\Livewire\Admin\Orders\OrderShow;
use App\Livewire\Admin\Coupons\CouponIndex;
use App\Livewire\Admin\Coupons\CouponCreate;
use App\Livewire\Admin\Coupons\CouponEdit;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/', ProductList::class)->name('home');
Route::get('/tienda', ProductList::class)->name('shop.index');
Route::get('/producto/{slug}', ProductShow::class)->name('shop.product');
Route::get('/categoria/{slug}', CategoryShow::class)->name('shop.category');


Route::get('/carrito', CartPage::class)->name('shop.cart');
Route::get('/checkout', CheckoutPage::class)->name('checkout')->middleware('auth');
Route::get('/pedido/{orderNumber}', OrderConfirmation::class)->name('order.confirmation')->middleware('auth');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('/webhook/stripe', [PaymentController::class, 'stripeWebhook'])->name('webhook.stripe');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

        Route::get('/pago/success/{order}', [PaymentController::class, 'success'])->name('payment.success');
        Route::get('/pago/paypal/success/{order}', [PaymentController::class, 'paypalSuccess'])->name('payment.paypal.success');
        Route::get('/pago/cancel/{order}', [PaymentController::class, 'cancel'])->name('payment.cancel');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    
    // Productos
    Route::get('/productos', ProductIndex::class)->name('products.index');
    Route::get('/productos/crear', ProductCreate::class)->name('products.create');
    Route::get('/productos/{id}/editar', ProductEdit::class)->name('products.edit');
    
    // Pedidos
    Route::get('/pedidos', OrderIndex::class)->name('orders.index');
    Route::get('/pedidos/{id}', OrderShow::class)->name('orders.show');
    
    // Cupones
    Route::get('/cupones', CouponIndex::class)->name('coupons.index');
    Route::get('/cupones/crear', CouponCreate::class)->name('coupons.create');
    Route::get('/cupones/{id}/editar', CouponEdit::class)->name('coupons.edit');
});

require __DIR__.'/auth.php';
