<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dressmeasurementnow extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'dress_id',
        'shirtitems_id' , 
        'skirtitems_id' , 
        'measurementnow_dress_name',
        'measurementnow_dress_number',
        'measurementnow_dress_unit',
        'count',
    ];
}
