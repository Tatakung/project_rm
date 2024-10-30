<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jewelrysetitem extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'jewelry_set_id',
        'jewelry_id',
    ];

       public function jewitem_m_to_o_jew(){
        return $this->belongsTo(Jewelry::class,'jewelry_id') ; 
    }

    public function jewitem_m_to_o_jewset(){
        return $this->belongsTo(Jewelryset::class,'jewelry_set_id') ; 
    }

}
