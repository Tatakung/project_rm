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
}
