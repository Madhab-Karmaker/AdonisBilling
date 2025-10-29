<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToSalon;

class Service extends Model
{
    use HasFactory, BelongsToSalon;

    protected $fillable = ['name', 'price', 'salon_id'];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }
}

