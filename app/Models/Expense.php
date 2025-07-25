<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'date',
        'expense_type',
        'expense_value',
        'employee_id',
    ];
    public function expense_many_to_one_user(){
        return $this->belongsTo(User::class,'employee_id') ; 
    }
}
