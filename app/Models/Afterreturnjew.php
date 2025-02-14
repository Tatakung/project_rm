<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Afterreturnjew extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'reservationfilter_id',
        'type',
        'price',
    ];
    public function afterreturnjew_one_to_one_re(){
        return $this->belongsTo(Reservationfilters::class,'reservationfilter_id') ; 
    }

}
