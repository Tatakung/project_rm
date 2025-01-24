<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JewelryHistory extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'jewelry_id',
        'old_price',
        'new_price',
    ];
    public function jewelryhistory_many_to_one_jew(){
        return $this->belongsTo(Jewelry::class,'jewelry_id') ; 
    }


}
