<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'dress_id',
        'jewelry_id',
        'shirtitems_id',
        'skirtitems_id',
        'start_date',
        'end_date',
        'status' , 
        'status_completed' , 
    ];
}
