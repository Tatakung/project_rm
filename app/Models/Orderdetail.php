<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orderdetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'jewelry__id',
        'dress_id',
        'order_id',
        'shirtitems_id',
        'skirtitems_id',
        'status_fix_measurement',
        'employee_id',
        'title_name',
        'late_charge',
        'real_pickup_date',
        'real_return_date',
        'pickup_date',
        'return_date',
        'type_dress',
        'type_order',
        'amount',
        'price',
        'deposit',
        'note',
        'damage_insurance',
        'total_damage_insurance',
        'cause_for_insurance',
        'cloth',
        'status_detail',
        'status_payment',
        'late_fee',
        'total_other_price',
        'color',
    ];
    //ตารางorderdetail เป็น M - 1 ของตาราง order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    //ตาราง orderdetail เป็น 1 - M ของตาราง decoration
    public function decorations()
    {
        return $this->hasMany(Decoration::class, 'order_detail_id');
    }

    //ตาราง orderdetail เป็น 1 - M ของตาราง cost
    public function costs()
    {
        return $this->hasMany(Cost::class, 'order_detail_id');
    }

    //ตาราง orderdetail เป็น 1 - M ของตาราง date
    public function dates()
    {
        return $this->hasMany(Date::class, 'order_detail_id');
    }

    // ความสัมพันธ์ที่จะดึง date ล่าสุด
    public function latestDate()
    {
        return $this->hasOne(Date::class, 'order_detail_id')->latest('pickup_date');
    }

    //ตาราง orderdetail เป็น 1 - M ของตาราง payment
    public function paymentstatuses()
    {
        return $this->hasMany(Paymentstatus::class, 'order_detail_id');
    }

    //ตาราง orderdetail เป็น 1 - M ของตาราง orderdetailstatuses
    public function orderdetailstatuses()
    {
        return $this->hasMany(Orderdetailstatus::class, 'order_detail_id');
    }

    //ตาราง orderdetail เป็น 1 - M ของตาราง imagerent
    public function imagerent()
    {
        return $this->hasMany(imagerent::class, 'order_detail_id');
    }


    //ตาราง orderdetail เป็น 1 - M ของตาราง fitting
    public function fitting()
    {
        return $this->hasMany(Fitting::class, 'order_detail_id');
    }

    //ตาราง orderdetail เป็น 1 - M ของตาราง measurementorderdetails
    public function measurementorderdetails()
    {
        return $this->hasMany(Measurementorderdetail::class, 'order_detail_id');
    }

    //ตาราง orderdetail เป็น 1 - M ของตาราง financial
    public function financial()
    {
        return $this->hasMany(Financial::class, 'order_detail_id');
    }

    public function detail_many_one_re()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }
    public function orderdetailmanytoonedress()
    {
        return $this->belongsTo(Dress::class, 'dress_id');
    }
    public function orderdetail_one_to_many_receipt()
    {
        return $this->hasMany(Receipt::class, 'order_detail_id');
    }
    public function orderdetail_one_to_one_SeparateRentability()
    {
        return $this->hasOne(SeparateRentability::class, 'order_detail_id');
    }
}
