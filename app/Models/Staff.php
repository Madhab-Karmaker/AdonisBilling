<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'role',
        'salon_id'
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function billItems()
    {
        return $this->belongsToMany(BillItem::class, 'bill_item_staff');
    }
}
