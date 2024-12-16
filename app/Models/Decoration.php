<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Decoration extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'order_detail_id',
        'decoration_description',
        'decoration_price',
        'adjustment_round_id',
        'fitting_id' , 
    ];

     //ตารางdecoraction เป็น M - 1 ของตาราง orderdetail
     public function orderdetail(){
        return $this->belongsTo(Orderdetail::class, 'order_detail_id');
    }
}
