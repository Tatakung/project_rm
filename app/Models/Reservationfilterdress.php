<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservationfilterdress extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'dress_id',
        'shirtitems_id',
        'skirtitems_id',
        'start_date',
        'end_date',
        'status',
        'status_completed',
        'reservation_id' ,
         
    ];

    public function filterdress_many_to_one_dress(){
        return $this->belongsTo(Dress::class,'dress_id') ; 
    }

    


    

    

    public function filterdress_many_to_one_shirt(){
        return $this->belongsTo(Shirtitem::class,'shirtitems_id') ; 
    }

    


    



    public function filterdress_many_to_one_skirt(){
        return $this->belongsTo(Skirtitem::class,'skirtitems_id') ; 
    }


    public function filterdress_one_to_many_repair(){
        return $this->hasMany(Repair::class,'reservationfilterdress_id') ; 
    }



}
