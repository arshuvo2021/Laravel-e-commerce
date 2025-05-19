<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'in_stock', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeExpensive($query)
    {
        return $query->where('price', '>', 100)->orderBy('price', 'desc');
    }
}
