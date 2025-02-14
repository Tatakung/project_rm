<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Afterreturndress extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'reservationfilterdress_id',
        'type',
        'price',
    ];
    public function afterdress_one_to_one_filterdress()
    {
        return $this->belongsTo(Reservationfilterdress::class, 'reservationfilterdress_id');
    }
}
