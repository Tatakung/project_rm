<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceHistory_Dress extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'dress_id',
        'old_price',
        'new_price',
    ];
    public function historydress_many_to_one_dress(){
        return $this->belongsTo(Dress::class,'dress_id') ; 
    }

    
}
