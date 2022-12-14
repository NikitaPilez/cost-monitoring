<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'body',
        'sms_id',
        'amount',
        'balance',
        'place',
        'buy_at',
        'is_accrual'
    ];

    protected function buyAt() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d.m.Y H:s'),
            set: fn ($value) => Carbon::parse($value)->format('Y-m-d H:i:s'),
        );
    }

    public function scopeFilter($query,  int $userId, $search = null)
    {
        if ($search !== null) {
            $query->where('body', 'like', '%' . $search . '%');
        }
        return $query->where('user_id', $userId)->orderBy('buy_at', 'DESC');
    }
}
