<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'customer_id',
        'total_quantity',
        'total_price',
        'total_deposit',
        'order_status',
    ];
    // ตาราง order เป็น M - 1 ของตาราง customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    // ตารางorder เป็น M - 1 ของตารางuser
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //ตารางorder เป็น 1 - M ของตาราง orderdetail
    public function orderdetails()
    {
        return $this->hasMany(Orderdetail::class, 'order_id');
    }
}
