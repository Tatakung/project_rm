<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceHistory_Shirt extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'shirtitems_id',
        'old_price',
        'new_price',
    ];
    public function historyshirt_many_to_one_shirt(){
        return $this->belongsTo(Shirtitem::class,'shirtitems_id') ; 
    }
}
