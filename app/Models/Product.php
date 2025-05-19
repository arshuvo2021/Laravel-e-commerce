<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function scopeExpensive($query)
    {
        return $query->where('price', '>', 100)
            ->orderBy('price', 'desc');
    }
}
