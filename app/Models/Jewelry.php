<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jewelry extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'type_jewelry_id',
        'jewelry_code',
        'jewelry_title_name',
        'jewelry_code_new' , 
        'jewelry_price',
        'jewelry_deposit',
        'damage_insurance',
        'jewelry_count',
        'jewelry_status',
        'jewelry_description',
        'jewelry_rental', 
        'repair_count' ,        
    ];

    //ตาราง jew เป็น M ต่อ 1 ของตาราง typejew
    public function jewelry_m_o_typejew(){
        return $this->belongsTo(Typejewelry::class,'type_jewelry_id') ; 
    }
    //jew เป็น 1 ต่อ M ของตาราง jewimage
    public function jewelryimages(){
        return $this->hasMany(Jewelryimage::class,'jewelry_id') ; 
    }


    //jew เป็น 1 ต่อ M ของตาราง jewitem
    public function jewelryitems(){
        return $this->hasMany(Jewelrysetitem::class,'jewelry_id') ; 
    }

    public function jewonetomanyreser(){
        return $this->hasMany(Reservation::class,'jewelry_id') ; 
    }
    public function jewonetomanyreserfil(){
        return $this->hasMany(Reservationfilters::class,'jewelry_id') ; 
    }
    public function jewonetomanychargejewelrys(){
        return $this->hasMany(ChargeJewelry::class,'additional_charge_id') ; 
    }
    public function jew_one_to_many_jewelryhistory(){
        return $this->hasMany(JewelryHistory::class,'jewelry_id') ; 
    }
}
