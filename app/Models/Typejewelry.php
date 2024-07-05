<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Typejewelry extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'type_jewelry_name',
        'specific_letter' , 
    ];
    //typejew เป็น 1ต่อ M ของ jew
    public function jewelrys(){
        return $this->hasMany(Jewelry::class,'type_jewelry_id') ; 
    }

}
