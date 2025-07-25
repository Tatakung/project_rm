<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dressmeaadjustment extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'dressmea_id',
        'order_detail_id',
        'new_size',
        'status',
        'name' , 
    ];
    public function dressmeaadjust_many_to_one_dressmea(){
        return $this->belongsTo(Dressmea::class,'dressmea_id') ; 
    }






}
