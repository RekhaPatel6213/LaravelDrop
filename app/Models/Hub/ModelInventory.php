<?php

namespace App\Models\Hub;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToPrimaryModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Territory\Territory;

class ModelInventory extends Model
{
    use BelongsToPrimaryModel, SoftDeletes;

    protected $fillable = [
        'inventory_id',
        'model_type',
        'model_id'
    ];

    public function getRelationshipToPrimaryModel(): string
    {
        return 'inventory';
    }

    public function model()
    {
        return $this->morphTo();
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    public function territories()
    {
        return $this->morphedByMany(Territory::class, 'model', 'model_inventories');
    }

    public function scopeInInventory($query, $inventoryId)
    {
        return $inventoryId ? $query->whereIn('inventory_id', [$inventoryId]) : $query;
    }

    public function scopeNotInInventory($query, $inventoryId)
    {
        return $inventoryId ? $query->whereNotIn('inventory_id', [$inventoryId]) : $query;
    }

    public function scopeOfModelType($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    public function scopeInModelId($query, $modelIds)
    {
        return $modelIds ? $query->whereIn('model_id', $modelIds) : $query;
    }

    public function scopeNotInModelId($query, $modelIds)
    {
        return $modelIds ? $query->whereNotIn('model_id', $modelIds) : $query;
    }
}
