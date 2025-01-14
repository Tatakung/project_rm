<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dressmea extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'dress_id',
        'shirtitems_id',
        'skirtitems_id',
        'mea_dress_name',
        'initial_mea',
        'initial_min',
        'initial_max',
        'current_mea'
    ];

    public function dressmea_one_to_many_dressmeaadjust(){
        return $this->hasMany(Dressmeaadjustment::class,'dressmea_id') ; 
    }



}
