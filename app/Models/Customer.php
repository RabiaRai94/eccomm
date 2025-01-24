<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
class Customer extends Authenticatable
{
    use HasFactory, Notifiable,Billable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'stripe_customer_id',
        'auth_user_id',
        'phone',
        'address',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    /**
     * Define the relationship with the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'auth_user_id');
    }
}
