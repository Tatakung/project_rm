<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservationfilters extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'jewelry_id',
        'jewelry_set_id',
        'start_date',
        'end_date',
        'status',
        'status_completed',
        'reservation_id' , 
    ];
    public function reservationtorefil()
    {
        return $this->belongsTo(Reservation::class,'reservation_id');
    }


    public function jewvationtorefil()
    {
        return $this->belongsTo(Jewelry::class,'jewelry_id');
    }


    public function re_one_many_repair()
    {
        return $this->hasMany(Repair::class, 'reservationfilter_id');
    }


}
