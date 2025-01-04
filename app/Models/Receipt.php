<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'order_id',
        'order_detail_id',
        'total_price' , 
        'receipt_type' , 
        
    ];

    public function receipt_many_to_one_order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function receipt_many_to_one_orderdetail()
    {
        return $this->belongsTo(Orderdetail::class, 'order_detail_id');
    }
}
