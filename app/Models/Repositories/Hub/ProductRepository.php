<?php

namespace App\Models\Repositories\Hub;

use App\Events\Hub\ProductInventoryLogEvent;
use App\Http\Traits\DispensaryTrait;
use App\Http\Traits\MediaTrait;
use App\Http\Traits\ProductTrait;
use App\Jobs\ElasticProductJob;
use App\Models\Hub\Product;
use App\Models\Hub\ProductDetail;
use App\Models\Hub\ProductVariant;
use Vanilo\Product\Models\ProductState;

class ProductRepository
{
    use MediaTrait;
    use DispensaryTrait;
    use ProductTrait;

    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getQueryBuilder(string $search = null, string $state, string $sortOn, string $sortOrder)
    {
        $queryBuilder = $this->product->with([
                                                'media',
                                                'productDetails.variant',
                                                'taxons' => function ($query4) {
                                                    $query4->with(['parent'])->orderBy('priority', 'ASC');
                                                },
                                            ])
                        ->when($search !== null, function ($query1) use ($search) {
                            $query1->where(function ($query2) use ($search) {
                                foreach ($this->product::SEARCH_FIELDS as $field) {
                                    $query2->orWhere($field, 'LIKE', '%'.$search.'%');
                                }

                                $query2->orWhereHas('taxons', function ($query3) use ($search) {
                                    $query3->where('name', 'LIKE', '%'.$search.'%');
                                });
                            });
                        })
                        ->whereHas('taxons')
                        ->where('state', $state)
                        ->whereNull('deleted_at')
                        ->orderBy($sortOn, $sortOrder);

        return $queryBuilder;
    }

    public function list(string $search = null, string $state, string $sortOn, string $sortOrder)
    {
        $products = $this->getQueryCategoryProduct($state, $search, $sortOn, $sortOrder)->get();

        $productObject = [];
        if ($products) {
            foreach ($products as $product) {
                $parentCategory = $product->parent_taxon ?? null;
                $priority = $product->taxon_priority ?? null;
                $category = $product->taxon ?? null;
                $productObject[$priority][$parentCategory][] = $this->formateProduct($product, $category);
            }
        }

        return $productObject;
    }

    public function formateProduct($product, string $category, $isExport = null)
    {
        $variants = $product->productDetails ? data_get($product->productDetails, '*.variant.name') : null;
        $prices = $product->productDetails ? data_get($product->productDetails, '*.price') : null;
        $wholesalePrices = $product->productDetails ? data_get($product->productDetails, '*.wholesale_price') : null;
        $stocks = $product->productDetails ? data_get($product->productDetails, '*.stock') : null;

        if ($isExport && $product->quantity_type === $this->product::GRAMS) {
            $stocks = $product->productDetails ? data_get($product->productDetails, '*.original_stock') : null;
            $wholesalePrices = $product->wholesale_price > 0 ? [$product->wholesale_price] : null;
        }

        $productObject = [
            'id' => $product->id,
            'name' => $product->name,
            'brand' => $product->brand,
            'logo' => $product->hasMedia('logo') ? $product->getFirstMedia('logo')->getUrl('thumb') : null,
            'category' => $category,
            'quantity_type' => $product->quantity_type,
            'variants' => $isExport ? implode(',', $variants) : $variants,
            'stocks' => $product->is_unlimited === $this->product::YES ? $this->product::UNLIMITED : ($isExport ? implode(',', $stocks) : $stocks),
            'prices' => $isExport ? implode(',', $prices) : $prices,
            'wholesale_prices' => $isExport && $wholesalePrices != null ? implode(',', $wholesalePrices) : $wholesalePrices,
        ];

        if ($isExport === $this->product::PDF) {
            $productObject['state'] = $product->state->value();
        }

        if (!$isExport) {
            $productObject['unallocatedQuantity'] = (int) 0;
            $productObject['priority'] = $product->priority;
        }

        return $productObject;
    }

    public function get(int $productId, array $with = null)
    {
        $product = $this->product->whereId($productId)->where('state', ProductState::ACTIVE);
        if ($with) {
            $product->with($with);
        }

        return $product->first();
    }

    public function updateOrCreate(array $requestData, Product $product = null)
    {
        $categoryId = $requestData['category_id'] ?? null;
        $oldStock = $product ? $product->stock : 0;
        if ($product === null) {
            $product = new Product();
            $product->sku = $this->product->generateUniqueId('Hub\Product', 'sku');
            $product->priority = $this->product->maxPriorityOfCategoryProduct($categoryId);
            $product->state = ProductState::ACTIVE;
        }

        $requestData = \array_filter($requestData, function ($value) {
            return ($value != '' || $value != null) ? true : false;
        });

        $productFill = $product->getFillable();
        $productData = array_filter(
            $requestData,
            function ($key) use ($productFill) {
                return in_array($key, $productFill) >= 0;
            },
            ARRAY_FILTER_USE_KEY
        );
        $product->fill($productData);
        $product->save();

        if (request()->method() !== 'PATCH') {
            $this->storeTaxonDetails($product, $categoryId); //Used ProductTrait function
            $this->storeProductDetails($product, $requestData);
            $this->createMedia($product, $this->product::MEDIATYPE, ($requestData['logo'] ?? null));

            if ($product->quantity_type !== $this->product::PREPACKAGED) {
                $this->updateOrCreateProductStock($product);
                event(new ProductInventoryLogEvent($product, ($product->stock ?? 0), $oldStock));
            }
        }
        dispatch(new ElasticProductJob($product));

        return $product;
    }

    public function storeProductDetails(Product $product, array $requestData)
    {
        $isUnlimited = $requestData['is_unlimited'] ?? $product->is_unlimited;

        //For Normal Product details save
        if ($product->quantity_type === $this->product::UNITS) {
            $productDetail = $this->storeSingleDetails($product, $requestData, $isUnlimited);
        }

        //Variant wise product details save
        if (isset($requestData['product_details']) && $product->quantity_type !== $this->product::UNITS) {
            $this->storeVariantDetails($product, $requestData, $isUnlimited);
        }
    }

    public function storeSingleDetails(Product $product, array $requestData)
    {
        $productDetail = $product->productDetails()->updateOrCreate(
            ['id' => $detail['id'] ?? null],
            $requestData
        );

        $this->deleteProductDetails($product, [$productDetail->id]);

        return $productDetail;
    }

    public function storeVariantDetails(Product $product, array $requestData, $isUnlimited)
    {
        $stock = $requestData['stock'] ?? 0;
        $requestData['wholesale_price'] = $requestData['wholesale_price'] ?? 0;
        $detailIds = [];

        if ('array' !== gettype($requestData['product_details'])) {
            $requestData['product_details'] = $this->stringToArray($requestData['product_details']);
        }

        foreach ($requestData['product_details'] as $detail) {
            $detailId = $detail['id'] ?? null;
            $variantId = $detail['variant_id'] ?? null;
            $pDetails = $this->getSingleProductDetail($product->productDetails, $detailId, $variantId);
            $oldStock = $pDetails->stock ?? 0;

            if ($pDetails === null) {
                $pDetails = new ProductDetail();
                $pDetails->product_id = $product->id;
            }

            $detail['stock'] = $isUnlimited === $this->product::YES ? 0 : ($product->quantity_type === $this->product::PREPACKAGED ? $detail['stock'] : $stock);
            $pDetails->fill($detail);

            $variant = $detail['variant_id'] ? ProductVariant::find($detail['variant_id']) : null;

            //Calculated Gram product Stock
            if ($product->quantity_type === $this->product::GRAMS) {
                $pDetails->original_stock = $detail['stock'];
                $pDetails->stock = $variant ? (int) ($detail['stock'] / $variant->limit_quantity) : $pDetails->stock;
                $pDetails->wholesale_price = $variant && $requestData['wholesale_price'] > 0 ? $requestData['wholesale_price'] * $variant->limit_quantity : $requestData['wholesale_price'];
            }
            $pDetails->save();

            \array_push($detailIds, $pDetails->id);
            if ($product->quantity_type === Product::PREPACKAGED) {
                $this->updateOrCreateProductDetailStock($pDetails);
                event(new ProductInventoryLogEvent($product, $pDetails->stock, $oldStock, $pDetails->id, $variant->name));
            }
        }
        $this->deleteProductDetails($product, $detailIds);
    }

    public function deleteProductDetails(Product $product, array $detailIds)
    {
        //Delete past product details when update product. Also product stock wallet delete.
        if ($product->has('productDetails')) {
            foreach ($product->productDetails as $detail) {
                if (!in_array($detail->id, $detailIds)) {
                    $detail->wallet()->delete();
                    $detail->delete();
                }
            }
        }
    }

    public function getSingleProductDetail($details, int $detailId = null, int $variantId = null)
    {
        if ($detailId) {
            return $details->where('id', $detailId)->first();
        }

        return $details->where('variant_id', $variantId)->first();
    }

    public function updateAll(array $requestData)
    {
        $productIds = explode(',', $requestData['product_ids']);
        unset($requestData['product_ids']);
        $productData = $requestData;
        $this->product->whereIn('id', $productIds)->update($productData);

        return ['message' => __('message.updateSuccess', ['name' => __('product.product')])];
    }

    public function deleteAll(array $requestData)
    {
        $productIds = explode(',', $requestData['product_ids']);
        $this->product->whereIn('id', $productIds)->delete();

        return ['message' => __('message.deleteSuccess', ['name' => __('product.product')])];
    }

    public function getQueryCategoryProduct(string $state, string $search = null, string $sortOn = null, string $sortOrder = null)
    {
        $dispensaryId = tenant('id');
        $queryBuilder = $this->product->select(
                                'products.id', 'products.name', 'products.brand', 'products.quantity_type', 'products.stock', 'products.wholesale_price', 'products.priority', 'products.is_unlimited', 'products.state', 't.name as taxon', 't.name as taxon', 'pt.name as parent_taxon', 't.priority as taxon_priority'
                            )
                            ->with(['media', 'productDetails.variant'])
                            ->leftjoin('model_taxons as mt', function ($join2) {
                                $join2->on('mt.model_id', '=', 'products.id');
                                $join2->where('mt.model_type', '=', 'product');
                            }
                            )
                            ->leftjoin('taxons as t', 't.id', '=', 'mt.taxon_id')
                            ->leftjoin('taxons as pt', 'pt.id', '=', 't.parent_id')
                            ->leftjoin('dispensary_categories as dt', function ($join2) use ($dispensaryId) {
                                $join2->on('t.id', '=', 'dt.taxon_id');
                                $join2->where('dt.dispensary_id', '=', $dispensaryId);
                            }
                            )
                            ->where([
                                ['products.state', '=', $state],
                                ['products.dispensary_id', '=', $dispensaryId],
                            ])
                            ->when($search !== null, function ($query1) use ($search) {
                                $query1->where(function ($query2) use ($search) {
                                    foreach ($this->product::SEARCH_FIELDS as $field) {
                                        $query2->orWhere($field, 'LIKE', '%'.$search.'%');
                                    }

                                    $query2->orWhereHas('taxons', function ($query3) use ($search) {
                                        $query3->where('name', 'LIKE', '%'.$search.'%');
                                    });
                                });
                            });

        if ($sortOn !== null && $sortOrder !== null) {
            $queryBuilder->orderBy($sortOn, $sortOrder);
        } else {
            $queryBuilder->orderBy('dt.priority', 'ASC')
                         ->orderBy('t.priority', 'ASC')
                         ->orderBy('products.priority', 'ASC');
        }

        return $queryBuilder;
    }

    public function getExportData($type)
    {
        $productObject = [];
        $products = $this->getQueryCategoryProduct(ProductState::ACTIVE)->get();
        if ($products) {
            foreach ($products as $product) {
                $productObject[] = $this->formateProduct($product, $product->taxon, $type);
            }
        }

        return $productObject;
    }

    public function checkProductWithFields(int $categoryId, array $fields = null, array $values = null)
    {
        return $this->product->where(function ($query1) use ($fields, $values) {
            if ($fields && $values && count($fields) === count($values)) {
                foreach ($fields as $key => $field) {
                    $query1->whereRaw('LOWER('.$field.') = ?', strtolower($values[$key]));
                }
            }
        })
                                    ->with(['productDetails'])
                                    ->ofActiveState()
                                    ->whereHas('taxons', function ($query3) use ($categoryId) {
                                        $query3->whereId($categoryId);
                                    })
                                    ->first();
    }

    public function importProductFormate($row, Product $product = null, int $categoryId = null)
    {
        $existProductDetail = $product->productDetails ?? null;
        $quantityType = $row['quantity_type'] ?? $this->product::UNITS;
        $existDetail = $existProductDetail ? $existProductDetail->first() : null;
        $newPrice = $this->getImportFieldValue($row['price'], 'price', $existDetail);
        $newStock = $row['is_unlimited'] ? 0 : $this->getImportFieldValue($row['stock'], 'stock', $existDetail);
        $newWholesale = $this->getImportFieldValue($row['wholesale_price'], 'wholesale_price', $existDetail);
        $productDetails = $this->formateProductDetails($quantityType, $row, $existProductDetail, ($product->stock ?? 0));

        return [
            'name' => $row['product_name'],
            'brand' => $row['brand'],
            'logo' => $row['logo'],
            'category_id' => $categoryId,
            'quantity_type' => $row['quantity_type'],
            'stock' => $quantityType === $this->product::PREPACKAGED ? 0 : $newStock,
            'previous_stock' => $quantityType === $this->product::PREPACKAGED ? 0 : ($existDetail ? $existDetail['stock'] : 0),
            'price' => $newPrice,
            'wholesale_price' => $quantityType === $this->product::PREPACKAGED ? null : $newWholesale,
            'is_unlimited' => $row['is_unlimited'] ? $this->product::YES : $this->product::NO,
            'is_new' => $product === null ? 1 : 0,
            'product_id' => $product->id ?? null,
            'product_details' => $productDetails,
            'total' => count($productDetails) === 0 ? ($newStock * $newPrice) : array_sum(data_get($productDetails, '*.total')),
        ];
    }

    public function formateProductDetails($quantityType, $row, $existProductDetail, $productStock)
    {
        $productDetails = [];
        $variants = $row['variant'];
        $key = 0;

        if ($quantityType === $this->product::GRAMS) {
            foreach ($row['variant'] as $variantId => $variantName) {
                $existDetail = $existProductDetail ? $existProductDetail->where('variant_id', $variantId)->first() : null;

                $newPrice = $this->getImportFieldValue($row['price'], 'price', $existDetail, $key);
                $newStock = $row['is_unlimited'] ? 0 : $this->getImportFieldValue($row['stock'], 'stock', $existDetail);

                $detail = [
                    'id' => $existDetail ? $existDetail->id : null,
                    'variant_id' => $variantId,
                    'price' => $newPrice,
                    'total' => $row['is_unlimited'] ? 0 : ($newStock * $newPrice),
                ];
                array_push($productDetails, $detail);
                ++$key;
            }
        } elseif ($quantityType === $this->product::PREPACKAGED) {
            foreach ($row['variant'] as $variantId => $variantName) {
                $existDetail = $existProductDetail ? $existProductDetail->where('variant_id', $variantId)->first() : null;

                $newPrice = $this->getImportFieldValue($row['price'], 'price', $existDetail, $key);
                $newStock = $row['is_unlimited'] ? 0 : $this->getImportFieldValue($row['stock'], 'stock', $existDetail, $key);

                $detail = [
                    'id' => $existDetail ? $existDetail->id : null,
                    'variant_id' => $variantId,
                    'stock' => $newStock,
                    'previous_stock' => $existDetail ? $existDetail['stock'] : 0,
                    'price' => $newPrice,
                    'wholesale_price' => $this->getImportFieldValue($row['wholesale_price'], 'wholesale_price', $existDetail, $key),
                    'total' => ($newStock * $newPrice),
                ];
                array_push($productDetails, $detail);
                ++$key;
            }
        }

        return $productDetails;
    }

    public function getColumn(int $productId, string $column)
    {
        $query = $this->product;
        $query = $column !== null ? $query->select($column) : $query;

        return $query->where('id', $productId)->first();
    }

    public function find($productId)
    {
        return $this->product->find($productId);
    }

    public function ajaxList()
    {
        return $this->product->select('id', 'name')->where('state', ProductState::ACTIVE)->get();
    }
}
