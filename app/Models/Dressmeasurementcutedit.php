<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dressmeasurementcutedit extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'adjustment_id',
        'order_detail_id' , 
        'old_size' , 
        'edit_new_size',
        'adjustment_number',
        'status' , 
    ];
}
