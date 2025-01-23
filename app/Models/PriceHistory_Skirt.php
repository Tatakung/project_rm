<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceHistory_Skirt extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'skirtitems_id',
        'old_price',
        'new_price',
    ];

    public function historyskirt_many_to_one_skirt(){
        return $this->belongsTo(Skirtitem::class,'skirtitems_id') ; 
    }



}
