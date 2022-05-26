<?php

namespace App\Http\Traits;

use App\Exceptions\OrderExitException;
use App\Models\Hub\Category;
use App\Models\Hub\Product;
use App\Models\Hub\ProductDetail;
use App\Models\Hub\ProductVariant;

trait ProductTrait
{
    public function storeTaxonDetails($model, int $categoryId = null)
    {
        if (isset($model->taxons[0])) {
            if ($model->taxons[0]->id !== $categoryId && $categoryId > 0) {
                $this->addOrRemoveTaxon($model, $model->taxons[0]->id, 'remove');
                $this->addOrRemoveTaxon($model, $categoryId);
            }
        } elseif ($model->taxons === null || $categoryId > 0) {
            $this->addOrRemoveTaxon($model, $categoryId);
        }
    }

    public function addOrRemoveTaxon($model, int $categoryId, string $type = 'add')
    {
        $taxon = Category::find($categoryId);

        if ($type === 'remove') {
            $model->removeTaxon($taxon);

            return true;
        }
        $model->addTaxon($taxon);

        return true;
    }

    public function inventoryActivity(Product $product, string $isUnlimited, int $productDetailId, int $stock)
    {
        //If product quantity is not unlimited then we create log of quantity
        if ($isUnlimited === Product::NO) {
            activity()
                ->performedOn($product)
                ->causedBy(Auth()->user())
                ->withProperties([
                    'product_id' => $product->id,
                    'product_detail_id' => $productDetailId,
                    'stock' => $stock,
                    'remain_stock' => $stock,
                ])
                ->log(Product::PRODUCT_INVENTORY_HUB);
        }
    }

    public function getVariantList(array $requestData)
    {
        return ProductVariant::whereHas('taxonomy', function ($query) use ($requestData) {
            if (isset($requestData['taxonomy'])) {
                $query->where('name', 'LIKE', '%'.$requestData['taxonomy'].'%');
            }
        })
                                ->orderBy('priority', 'ASC')->get()->toArray();
    }

    public function updateOrCreateProductStock(Product $product)
    {
        $this->updateTransaction($product, $product->id);

        //Calculated product Stock
        if ($product->productDetails) {
            $pDetails = $product->productDetails;
            foreach ($pDetails as $pDetail) {
                $variant = $pDetail->variant;
                $pDetail->original_stock = $variant ? $product->stock : null;
                $pDetail->stock = $variant ? (int) ($product->stock / $variant->limit_quantity) : $product->stock;
                $pDetail->save();
            }
        }
    }

    public function updateOrCreateProductDetailStock(ProductDetail $productDetail)
    {
        $this->updateTransaction($productDetail, $productDetail->product_id);
    }

    public function updateTransaction($model, int $modelId)
    {
        $transaction = $model->transactions->first() ?? null;

        if ($transaction === null) {
            $model->deposit((int) $model->stock, ['productId' => $modelId]);
        }

        if ($transaction && (int) $transaction->amount !== (int) $model->stock) {
            $transaction->amount = (int) $model->stock;
            $transaction->save();
            $model->wallet->refreshBalance();
        }
    }

    public function getImportFieldValue(array $data, string $field, $existDetail, int $key = 0)
    {
        if ($data[$key] && $existDetail && $field === Product::STOCK) {
            return $existDetail[$field] + $data[$key];
        } elseif ($data[$key]) {
            return $data[$key];
        }

        return 0;
    }
}
