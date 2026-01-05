<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyConsumption extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'decimal:2',
    ];

    /* =========================
     | RELACIONES
     ========================= */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}