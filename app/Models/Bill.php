<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

    class Bill extends Model
    {
        
        public function items() {
        return $this->hasMany(BillItem::class);
    }

    public function receptionist() {
        return $this->belongsTo(User::class, 'receptionist_id');
    }

}
