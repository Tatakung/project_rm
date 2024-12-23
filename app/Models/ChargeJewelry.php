<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChargeJewelry extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'additional_charge_id',
        'jewelry_id',
        'jewelrys_id' , 
    ];
    public function char_m_to_add(){
        return $this->belongsTo(AdditionalChange::class,'additional_charge_id') ; 
    }
    public function char_many_to_one_jewelry(){
        return $this->belongsTo(Jewelry::class,'jewelry_id') ; 
    }

}
