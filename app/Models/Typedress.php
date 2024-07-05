<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Typedress extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'type_dress_name',
        'specific_letter' , 
    ];

    // typedress เป็น 1- M ของ dress
    public function dresses(){
        return $this->hasMany(Dress::class,'type_dress_id') ; 
    }
}
