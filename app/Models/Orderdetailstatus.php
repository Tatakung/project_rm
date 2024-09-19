<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orderdetailstatus extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'order_detail_id',
        'status',
        'clean_id' , 
        'repair_id' , 
    ];

    //ตารางorderdetailstatus เป็น M - 1 ของตาราง orderdetail
    public function orderdetailstatusesManytoOneorderdetail()
    {
        return $this->belongsTo(Orderdetail::class, 'order_detail_id');
    }
}
