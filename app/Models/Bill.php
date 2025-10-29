<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

    class Bill extends Model
    {
        protected $fillable = [
            'customer_name',
            'total_price',
            'salon_id',
            'receptionist_id',
        ];

        
        public function salon(){
            return $this->belongsTo(Salon::class);
        }

        public function items() {
            return $this->hasMany(BillItem::class);
        }

        public function receptionist() {
            return $this->belongsTo(User::class, 'receptionist_id');
        }

        public function calculateTotalPrice() {
            return $this->items->sum( 
                function($item){
                    return item->price ;
                }
            );
        }


}
