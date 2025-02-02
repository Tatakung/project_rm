<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shirtitem extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'dress_id',
        'shirtitem_price',
        'shirtitem_deposit',
        'shirtitem_status',
        'shirtitem_rental',
        'shirt_damage_insurance',
        'repair_count' , 
        'shirt_adjustment' , 
    ];

    // shirtitem เป็น M - 1 ของ dress
    public function shirtitemmtodress(){
        return $this->belongsTo(Dress::class,'dress_id') ; 
    }
    public function shirt_one_to_many_historyshirt(){
        return $this->hasMany(PriceHistory_Shirt::class,'shirtitems_id') ; 
    }

    public function shirt_one_to_many_filterdress(){
        return $this->hasMany(Reservationfilterdress::class,'shirtitems_id') ; 
    }

    




}
