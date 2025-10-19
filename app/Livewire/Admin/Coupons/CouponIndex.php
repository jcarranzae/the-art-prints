<?php

// app/Livewire/Admin/Coupons/CouponIndex.php
namespace App\Livewire\Admin\Coupons;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use App\Models\Coupon;

#[Layout('layouts.admin')]
#[Title('Cupones - Admin')]
class CouponIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function deleteCoupon($couponId)
    {
        Coupon::findOrFail($couponId)->delete();
        session()->flash('message', 'Cupón eliminado');
    }

    public function toggleStatus($couponId)
    {
        $coupon = Coupon::findOrFail($couponId);
        $coupon->update(['is_active' => !$coupon->is_active]);
    }

    public function render()
    {
        $query = Coupon::query();

        if ($this->search) {
            $query->where('code', 'like', '%' . $this->search . '%');
        }

        $coupons = $query->latest()->paginate(20);

        return view('livewire.admin.coupons.coupon-index', compact('coupons'));
    }
}