<?php

namespace App\Models;

use Carbon\Carbon;
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
        'buy_at'
    ];

    protected function buyAt() : Attribute
    {
        return Attribute::make(
            set: fn ($value) => Carbon::createFromTimestamp($value)->toDateTimeString()
        );
    }
}
