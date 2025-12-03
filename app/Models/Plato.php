<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    use HasFactory;

    protected $fillable = [
        'descripcion',
        'user_id'
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con contenidos
    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    // Relación directa a productos a través de contents
    public function products()
    {
        return $this->belongsToMany(Product::class, 'contents')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }
}
