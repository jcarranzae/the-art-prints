<?php

// app/Livewire/Admin/Coupons/CouponCreate.php
namespace App\Livewire\Admin\Coupons;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\Coupon;

#[Layout('layouts.admin')]
#[Title('Crear Cupón - Admin')]
class CouponCreate extends Component
{
    #[Validate('required|string|max:50|unique:coupons,code')]
    public $code = '';

    #[Validate('nullable|string|max:255')]
    public $description = '';

    #[Validate('required|in:percentage,fixed')]
    public $type = 'percentage';

    #[Validate('required|numeric|min:0')]
    public $value = '';

    #[Validate('nullable|numeric|min:0')]
    public $min_purchase = '';

    #[Validate('nullable|integer|min:1')]
    public $usage_limit = '';

    #[Validate('nullable|integer|min:1')]
    public $usage_per_user = '';

    public $is_active = true;

    #[Validate('nullable|date')]
    public $starts_at = '';

    #[Validate('nullable|date|after_or_equal:starts_at')]
    public $expires_at = '';

    public function updatedCode()
    {
        $this->code = strtoupper($this->code);
    }

    public function save()
    {
        $this->validate();

        // Validación adicional para porcentaje
        if ($this->type === 'percentage' && $this->value > 100) {
            $this->addError('value', 'El porcentaje no puede ser mayor a 100%');
            return;
        }

        Coupon::create([
            'code' => strtoupper($this->code),
            'description' => $this->description,
            'type' => $this->type,
            'value' => $this->value,
            'min_purchase' => $this->min_purchase ?: null,
            'usage_limit' => $this->usage_limit ?: null,
            'usage_per_user' => $this->usage_per_user ?: null,
            'is_active' => $this->is_active,
            'starts_at' => $this->starts_at ?: null,
            'expires_at' => $this->expires_at ?: null,
        ]);

        session()->flash('message', 'Cupón creado correctamente');
        return redirect()->route('admin.coupons.index');
    }

    public function render()
    {
        return view('livewire.admin.coupons.coupon-create');
    }
}