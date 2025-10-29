<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    protected $fillable = [
        'bill_id',
        'service_id',
        'price',
        'quantity',
    ];
    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function bill() {
        return $this->belongsTo(Bill::class);
    }

}
