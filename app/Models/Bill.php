<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToSalon;

    class Bill extends Model
    {
        use BelongsToSalon;
        protected $fillable = [
        'customer_name',
        'customer_phone',
        'total_amount',
        'user_id',
        'salon_id'
    ];


        
        public function salon(){
            return $this->belongsTo(Salon::class);
        }

        public function items() {
            return $this->hasMany(BillItem::class);
        }

        public function creator() {
            return $this->belongsTo(User::class, 'user_id');
        }

        public function calculateTotalPrice() {
            return $this->items->sum( 
                function($item){
                    return item->price ;
                }
            );
        }


}
