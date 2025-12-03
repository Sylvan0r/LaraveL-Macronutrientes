<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'plato_id',
        'product_id',
        'cantidad'
    ];

    public function plato()
    {
        return $this->belongsTo(Plato::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
