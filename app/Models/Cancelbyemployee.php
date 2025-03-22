<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cancelbyemployee extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'employee_id',
        'order_detail_id',
    ];
    public function cancelbyemployee_one_to_one_orderdetail()
    {
        return $this->belongsTo(Orderdetail::class, 'order_detail_id');
    }
    public function cancelbyemployee_many_to_one_user(){
        return $this->belongsTo(User::class, 'employee_id');
    }
}
