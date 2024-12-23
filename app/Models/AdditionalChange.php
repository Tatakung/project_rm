<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdditionalChange extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'order_detail_id',
        'charge_type',
        'amount',
    ];
    public function chargejewelrys()
    {
        return $this->hasMany(ChargeJewelry::class, 'additional_charge_id');
    }
}
