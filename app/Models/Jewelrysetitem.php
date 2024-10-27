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
}
