<?php

// app/Livewire/Admin/Coupons/CouponEdit.php
namespace App\Livewire\Admin\Coupons;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use App\Models\Coupon;

#[Layout('layouts.admin')]
class CouponEdit extends Component
{
    public Coupon $coupon;

    #[Validate('required|string|max:50')]
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

    public function mount($id)
    {
        $this->coupon = Coupon::findOrFail($id);

        $this->code = $this->coupon->code;
        $this->description = $this->coupon->description;
        $this->type = $this->coupon->type;
        $this->value = $this->coupon->value;
        $this->min_purchase = $this->coupon->min_purchase;
        $this->usage_limit = $this->coupon->usage_limit;
        $this->usage_per_user = $this->coupon->usage_per_user;
        $this->is_active = $this->coupon->is_active;
        $this->starts_at = $this->coupon->starts_at?->format('Y-m-d');
        $this->expires_at = $this->coupon->expires_at?->format('Y-m-d');
    }

    public function updatedCode()
    {
        $this->code = strtoupper($this->code);
    }

    public function update()
    {
        $this->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $this->coupon->id,
            'description' => 'nullable|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        if ($this->type === 'percentage' && $this->value > 100) {
            $this->addError('value', 'El porcentaje no puede ser mayor a 100%');
            return;
        }

        $this->coupon->update([
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

        session()->flash('message', 'Cupón actualizado correctamente');
        return redirect()->route('admin.coupons.index');
    }

    public function render()
    {
        return view('livewire.admin.coupons.coupon-edit')
            ->title('Editar Cupón ' . $this->coupon->code . ' - Admin');
    }
}