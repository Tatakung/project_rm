<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dressmeasurement extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'dress_id',
        'shirtitems_id',
        'skirtitems_id',
        'measurement_dress_name',
        'measurement_dress_number',
        'measurement_dress_unit',
    ];

  // dressme เป็น M - 1 ของ dress
  public function dress(){
    return $this->belongsTo(Dress::class,'dress_id') ; 
}




}
