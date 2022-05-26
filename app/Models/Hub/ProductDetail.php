<?php

namespace App\Models\Hub;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToPrimaryModel;
use Vanilo\Support\Traits\BuyableModel;
use Vanilo\Contracts\Buyable;
use Vanilo\Support\Traits\BuyableNoImage;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Wallet;

class ProductDetail extends Model implements Buyable, Wallet
{
    use BelongsToPrimaryModel, HasWallet;
    use BuyableModel, BuyableNoImage;

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'variant_id',
        'wholesale_price',
        'price',
        'stock',
        'original_stock'
    ];

    public function getRelationshipToPrimaryModel(): string
    {
        return 'product';
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function inventories() {
        return $this->hasMany(ProductInventory::class, 'product_detail_id','id');
    }

    public function scopeOfProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function isOnStock(int $quantity = 1): bool
    {
        return $this->stock > 0 && $this->stock >= $quantity;  
    }
}
