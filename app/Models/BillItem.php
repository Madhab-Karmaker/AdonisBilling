<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function bill() {
        return $this->belongsTo(Bill::class);
    }

}
