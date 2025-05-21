<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'in_stock',
        'category_id',
        'description',
        'stock'
    ];

    protected $casts = [
        'in_stock' => 'boolean',
        'price' => 'decimal:2',
        'stock' => 'integer'
    ];

    protected $attributes = [
        'stock' => 0,
        'in_stock' => true
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['category'];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope a query to only include expensive products.
     */
    public function scopeExpensive($query)
    {
        return $query->where('price', '>', 100)->orderBy('price', 'desc');
    }

    /**
     * Scope a query to only include in-stock products.
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope a query to only include products in a specific category.
     */
    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}
