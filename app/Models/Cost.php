<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cost extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'order_detail_id',
        'cost_type',
        'cost_value',
    ];
    
    //ตารางcost เป็น M - 1 ของตาราง orderdetail
    public function costManytoOneorderdetail(){
        return $this->belongsTo(Orderdetail::class, 'order_detail_id');
    }
}
