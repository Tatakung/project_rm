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
    ];

    // shirtitem เป็น M - 1 ของ dress
    public function shirtitemmtodress(){
        return $this->belongsTo(Dress::class,'dress_id') ; 
    }

}
