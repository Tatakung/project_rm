<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Repair extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'dress_id',
        'shirtitems_id',
        'skirtitems_id',
        'repair_description',
        'repair_status',
        'repair_type' , 
        'reservation_id' ,
        'clean_id' , 
        'reservationfilter_id',
        'reservationfilterdress_id' , 
    ];

    public function repair_many_to_one_reservationfilter()
    {
        return $this->belongsTo(Reservationfilters::class,'reservationfilter_id');
    }

    public function repair_many_to_one_reser(){
        return $this->belongsTo(Reservation::class , 'reservation_id') ; 
    }
    public function repair_many_to_one_filerdress(){
        return $this->belongsTo(Reservationfilterdress::class,'reservationfilterdress_id') ; 
    }

}
