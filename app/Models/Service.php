<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public function billItems() {
        return $this->hasMany(BillItem::class);
    }

}
