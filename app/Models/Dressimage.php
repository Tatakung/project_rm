<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dressimage extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'dress_id',
        'dress_image',
    ];
    // dressimage เป็น M - 1 ของ dress
    public function dress()
    {
        return $this->belongsTo(Dress::class, 'dress_id');
    }
}
