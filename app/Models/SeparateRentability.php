<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeparateRentability extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'order_detail_id',
        'separate_rentable',
    ];


    public function SeparateRentability_one_to_one_order_detail_id()
    {
        return $this->belongsTo(Orderdetail::class, 'order_detail_id');
    }

    


}
