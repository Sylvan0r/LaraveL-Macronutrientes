<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'descripcion',
        'user_id',
        'is_favorite'
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

    // app/Models/Plato.php
    public function products()
    {
        return $this->belongsToMany(Product::class, 'plato_product')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_plato')->withPivot('quantity')->withTimestamps();
    }
}