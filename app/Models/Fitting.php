<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fitting extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'order_detail_id',
        'fitting_date',
        'fitting_real_date',
        'fitting_note',
        'fitting_status',
    ];

    //ตาราง fitting เป็น M - 1 ของตาราง orderdetail
    public function fittingManytoOneorderdetail()
    {
        return $this->belongsTo(Orderdetail::class, 'order_detail_id');
    }
}
