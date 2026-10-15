<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable (Slide 28).
     */
    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'price',
        'stock',
        'minimum_stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'minimum_stock' => 'integer',
    ];

    /**
     * Relasi Many-to-One ke Category (Slide 33)
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi Many-to-Many ke Supplier via product_suppliers
     */
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'product_suppliers')
                    ->withPivot('supplier_price')
                    ->withTimestamps();
    }

    /**
     * Relasi One-to-Many ke StockMovement
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class)->latest();
    }

    /**
     * Helper cek apakah stok menipis/kritis
     */
    public function isLowStock(): bool
    {
        return $this->stock <= $this->minimum_stock;
    }
}
