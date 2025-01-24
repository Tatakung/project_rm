<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jewelryset extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'set_name',
        'set_price',
        'set_status',
    ];
    //jewset เป็น 1 ต่อ M ของตาราง jewitem
    public function jewsetone_many_jewelryitems()
    {
        return $this->hasMany(Jewelrysetitem::class, 'jewelry_set_id');
    }
    public function jewsetone_many_re()
    {
        return $this->hasMany(Reservation::class, 'jewelry_set_id');
    }

    
    public function jewelryset_one_to_many_jewsethistory(){
        return $this->hasMany(JewelrySetHistory::class,'jewelry_set_id') ; 
    }


}
