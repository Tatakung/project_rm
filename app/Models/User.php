<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use SoftDeletes;
    protected $fillable = [
        'name',
        'email',    
        'password',
        'lname',
        'is_admin',
        'phone',
        'start_date',
        'birthday',
        'address',
        'image',
        'status'

    ];
    //  ตาราง user เปฌน 1 - M  ของตาราง order
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function user_one_to_many_receipt(){
        return $this->hasMany(Receipt::class,'employee_id') ;
    }

    public function user_one_to_many_receiptreturn(){
        return $this->hasMany(ReceiptReturn::class,'employee_id') ;
    }

    public function user_one_to_many_expense(){
        return $this->hasMany(Expense::class,'employee_id') ; 
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
