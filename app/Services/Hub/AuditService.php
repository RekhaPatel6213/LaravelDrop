<?php

namespace App\Services\Hub;
//use App\Models\Repositories\Contracts\Hub\AuditInterface;
use App\Models\Hub\Product;
use App\Models\Hub\ProductInventory;
use App\Models\Hub\BulkTransfer;
use App\Models\Repositories\Hub\ProductInventoryRepository;
use App\Http\Traits\AuditTrait;
use App\Http\Traits\ProductInventoryTrait;
use App\Http\Traits\ServiceTrait;
use App\Http\Traits\ProductTrait;
use App\Exceptions\InventoryException;
use PDF;
use Carbon\Carbon;

class AuditService
{
    use AuditTrait, ProductInventoryTrait, ServiceTrait, ProductTrait;

 	protected $repository, $inventoryRepository, $inventoryModelType;

    public function __construct($auditRepository)
    {
        //$this->repository = new AuditInterface;
        $this->repository = $auditRepository;
        $this->inventoryRepository = new ProductInventoryRepository;
        $this->inventoryModelType = $this->getInventoryModelType();
        $this->productInventoryModelType = $this->getProductInventoryModelType();
    }

    public function getProducts(array $requestData)
	{
        $modelType = $requestData['model_type'];
		$modelId = $requestData['model_id'];
		$categoryId = null;

		if ($modelType === ProductInventory::INVENTORY) {
            $modelId = (int)$modelId !== BulkTransfer::UNALLOCATED_ID ? (int) $modelId : null;
        } elseif ($modelType === ProductInventory::CATEGORY) {
            $modelType = $this->productInventoryModelType;
            $categoryId = $modelId;
            $modelId = null;
        }

		$products = $this->inventoryRepository->getProductWithInventoryByModel($modelType, $modelId, null, null, $categoryId);
        return $this->getProductInventories($products, $modelType, $modelId);
	}

    public function getProductInventories(object $products, string $modelType, ?int $modelId)
    {
        $productObject = [];
        $productIds = $products->pluck('id')->toArray();
        $totalStocks = $this->inventoryRepository->getTotalInventoryStock($modelType, $productIds);

        if ($products) {
            foreach ($products as $product) {
                $productId = $product->id;
                $totalAllocatedStocks =  $totalStocks->where('product_id', $productId)->pluck('stock','product_id')->toArray();
                if($product->quantity_type === Product::PREPACKAGED){
                    $totalAllocatedStocks =  $totalStocks->where('product_id', $productId)->pluck('stock','product_detail_id')->toArray();
                }
                $productObject[$productId] = $this->auditProductFormate($product, $totalAllocatedStocks, $modelId);
            }
        }
        return $productObject;
    }

    public function list(array $requestData)
    {
        $search = $requestData['search'] ?? null;
        $sortOn = $requestData['sortOn'] ?? 'audits.id';
        $sortOrder = $requestData['sort'] ?? 'asc';
        return $this->repository->list($search, $sortOn, $sortOrder);
    }

    public function store(array $requestData)
    {
        //return $requestData;
        $products = $requestData['products'];
        $modelType = $requestData['model_type'];
        $modelId = $requestData['model_id'];
        //$categoryId = null;

        if ($modelType === ProductInventory::INVENTORY) {
            $modelId = (int)$modelId !== BulkTransfer::UNALLOCATED_ID ? (int) $modelId : null;
        } elseif ($modelType === ProductInventory::CATEGORY) {
            $modelType = $this->productInventoryModelType;
            //$categoryId = $modelId;
            $modelId = null;
        }

        $productIds = data_get($products,'*.product_id');
        $productObjects = Product::with([
                                    'transactions',
                                    'productDetails' => function ($query) {
                                         $query->with(['variant','transactions']); 
                                     }
                                ])
                                ->whereIn('id', $productIds)->get();

        if ($products) {
            foreach ($products as $keyP => $product) {
                $productId = $product['product_id'];
                $productObject = $productObjects->where('id', $productId)->first();
                $quantityType = $productObject->quantity_type;
                $isUnlimited = $productObject->is_unlimited;
                $stock = $productObject->stock;

                foreach ($product['product_details'] as $keyD => $detail) {
                    $newStock = $detail['new_stock'];
                    $productObject->is_unlimited = $isUnlimited !== $detail['is_unlimited'] ? $detail['is_unlimited'] : $isUnlimited;
                    $productDetailId = $quantityType === Product::PREPACKAGED ? ($detail['product_detail_id'] ?? null) : null;

                    if ($quantityType === Product::PREPACKAGED && $newStock > 0 && $detail['is_unlimited'] === Product::NO) {
                        $pDetails = $productObject->productDetails->where('id', $productDetailId)->first();
                        if($newStock !== (int) $pDetails->stock){
                            $pDetails->stock = $newStock;
                            $pDetails->save();
                            $this->updateOrCreateProductDetailStock($pDetails);
                        }
                    }

                    if ($quantityType !== Product::PREPACKAGED && $newStock > 0 && $detail['is_unlimited'] === Product::NO) {
                        $productObject->stock = $newStock > 0 ? $newStock : $productObject->stock;
                        $this->updateOrCreateProductStock($productObject);
                    }
                    $productObject->save();

                    if ($newStock === 0 && $isUnlimited === $detail['is_unlimited']) {
                        unset($requestData['products'][$keyP]['product_details'][$keyD]);
                    }
                }

                if (($newStock > 0 
                    && $productObject->stock !== $stock)  || ($isUnlimited !== $productObject->is_unlimited)) {
                    $productObject->save();
                }
            }
        }
        return $this->repository->create($requestData);
    }

    public function get(int $auditId)
    {
        return $this->repository->getAudit($auditId);
    }

    public function export(int $auditId)
    {
        $audit = $this->repository->getAudit($auditId);
        $fileName = tenant('id').'_'.config('constants.ExportFilePrefix').'Audit_' . time() . '.pdf';

        $pdf = PDF::loadView('export/audit', compact('audit'));
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $date = Carbon::now()->format('d-m-Y H:i:s');
        $canvas->page_text(95 - $canvas->get_text_width($date, null, 8), 815, $date, null, 10, array(0, 0, 0));
        $canvas->page_text(530, 815, "Page {PAGE_NUM}/{PAGE_COUNT}", null, 10, array(0, 0, 0));
        $pdf->save(storage_path() . '/' . $fileName);
        return $pdf->download($fileName);
    }
}