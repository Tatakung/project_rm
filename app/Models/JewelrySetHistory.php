<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class JewelrySetHistory extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'jewelry_set_id',
        'old_price',
        'new_price',
    ];


    public function jewelrysethistory_many_to_one_jewelryset(){
        return $this->belongsTo(Jewelryset::class,'jewelry_set_id') ; 
    }
    

}
