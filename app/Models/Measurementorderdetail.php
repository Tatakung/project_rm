<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Measurementorderdetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'order_detail_id',
        'measurement_name',
        'measurement_number',
        'measurement_unit',
    ];
    //ตาราง measurementorderdetail เป็น M - 1 ของตาราง orderdetail
    public function measurementorderdetailManytoOneorderdetail()
    {
        return $this->belongsTo(Orderdetail::class, 'order_detail_id');
    }
}
