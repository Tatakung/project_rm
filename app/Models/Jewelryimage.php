<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jewelryimage extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'jewelry_id',
        'jewelry_image',
    ];

    //jewimage เป็น M ต่อ 1 ต่อ jew
    public function jewim_m_to_o_jew(){
        return $this->belongsTo(Jewelry::class,'jewelry_id') ; 
    }

}
