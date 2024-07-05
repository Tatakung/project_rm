<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'customer_fname',
        'customer_lname',
        'customer_phone',
        'id_line',
    ];
    //customer เป็น 1-M ของตาราง order
    public function orders(){
        return $this->hasMany(Order::class,'customer_id');
    }
}
