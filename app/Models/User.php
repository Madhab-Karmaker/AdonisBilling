<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'salon_id'];
    protected $hidden = ['password', 'remember_token'];

    public function salon() {
        return $this->belongsTo(Salon::class);
    }
}
