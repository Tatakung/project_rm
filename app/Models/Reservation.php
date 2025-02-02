<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'dress_id',
        'jewelry_id',
        'jewelry_set_id',
        'shirtitems_id',
        'skirtitems_id',
        'start_date',
        'end_date',
        'status',
        'status_completed',
        'reservationfilter_id',
    ];

    public function re_one_many_details()
    {
        return $this->hasMany(Orderdetail::class, 'reservation_id');
    }


    public function re_one_many_refil()
    {
        return $this->hasMany(Reservationfilters::class, 'reservation_id');
    }

    public function re_one_many_refildress()
    {
        return $this->hasMany(Reservationfilterdress::class, 'reservation_id');
    }




    public function resermanytoonejew()
    {
        return $this->belongsTo(Jewelry::class, 'jewelry_id');
    }
    public function resermanytoonejewset()
    {
        return $this->belongsTo(Jewelryset::class, 'jewelry_set_id');
    }
    public function reservation_many_to_one_dress()
    {
        return $this->belongsTo(Dress::class, 'dress_id');
    }

    public function reser_one_to_many_clean()
    {
        return $this->hasMany(Clean::class, 'reservation_id');
    }
    public function reser_one_to_many_repair(){
        return $this->hasMany(Repair::class , 'reservation_id') ; 
    }
}
