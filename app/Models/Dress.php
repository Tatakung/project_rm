<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dress extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'type_dress_id',
        'dress_code',
        'dress_title_name',
        'dress_color',
        'dress_price',
        'dress_deposit',
        'dress_count',
        'dress_status',
        'dress_description',
        'dress_rental',
        'dress_code_new' , 
        'separable' , 
        'damage_insurance',
        'repair_count',
        'dress_adjustment',
        'source_type' , 
    ];

    // dress เป็น M - 1 ของ type
    public function typedress(){
        return $this->belongsTo(Typedress::class,'type_dress_id') ; 
    }

    // dress เป็น 1 - M ของ dressimage
    public function dressimages(){
        return $this->hasMany(Dressimage::class,'dress_id') ; 
    }

    public function dress_one_to_many_orderdetail(){
        return $this->hasMany(Orderdetail::class,'dress_id') ; 
    }




    // dress เป็น 1 - M ของ dressmeasurement
    public function dressmeasurements(){
        return $this->hasMany(Dressmeasurement::class,'dress_id') ; 
    }
    
      // dress เป็น 1 - M ของ dressmeasurement
      public function dressmeasurementnows(){
        return $this->hasMany(Dressmeasurementnow::class,'dress_id') ; 
    }



    // dress เป็น 1 - M ของ shirtitem
    public function shirtitems(){
        return $this->hasMany(Shirtitem::class,'dress_id')  ; 
    }

    public function skirtitems(){
        return $this->hasMany(Skirtitem::class,'dress_id')  ; 
    }

    public function dress_one_to_many_reservation(){
        return $this->hasMany(Reservation::class,'dress_id')  ; 
    }

    public function dress_one_to_many_historydress(){
        return $this->hasMany(PriceHistory_Dress::class,'dress_id') ; 
    }
    public function dress_one_to_many_filterdress(){
        return $this->hasMany(Reservationfilterdress::class,'dress_id') ; 
    }






}
