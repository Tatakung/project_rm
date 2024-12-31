<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clean extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'dress_id',
        'shirtitems_id',
        'skirtitems_id',
        'clean_description',
        'clean_status',
        'reservation_id' , 
    ];
    public function clean_one_to_one_reser(){
        return $this->belongsTo(Reservation::class,'reservation_id') ; 
    }
}
