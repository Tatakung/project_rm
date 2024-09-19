<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skirtitem extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'dress_id',
        'skirtitem_price',
        'skirtitem_deposit',
        'skirtitem_status',
        'skirtitem_rental',
        'skirt_damage_insurance',
        'repair_count' , 
    ];

    // skirtitem เป็น M - 1 ของ dress
    public function skirtitemmtodress(){
        return $this->belongsTo(Dress::class,'dress_id') ; 
    }
}
