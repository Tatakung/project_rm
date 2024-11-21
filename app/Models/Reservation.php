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
        'status' , 
        'status_completed' , 
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


    public function resermanytoonejew(){
        return $this->belongsTo(Jewelry::class,'jewelry_id') ; 
    }
    public function resermanytoonejewset(){
        return $this->belongsTo(Jewelryset::class,'jewelry_set_id') ; 
    }


}
