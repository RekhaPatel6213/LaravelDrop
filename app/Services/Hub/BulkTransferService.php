<?php
namespace App\Services\Hub;

use App\Http\Traits\ProductInventoryTrait;
use App\Models\Hub\Product;
use App\Models\Hub\BulkTransfer;
use App\Models\Hub\ProductInventory;
use App\Models\Repositories\Hub\ProductInventoryRepository;
use App\Jobs\ElasticProductJob;
use App\Models\Hub\ProductDetail;
use App\Models\Activity;
use Vanilo\Product\Models\ProductState;

class BulkTransferService
{
	use ProductInventoryTrait;
	protected $repository;
	private $modelType;

    public function __construct()
    {
        $this->repository = new ProductInventoryRepository;
        $this->modelType = ProductInventory::INVENTORY;
    }

    public function get(int $transferId)
    {
		return $this->repository->getBulkTransferDetail($transferId);
    }

	public function bulkTransfer(array $requestData)
	{
		$fromInventoryId = $requestData['from_inventory_id'];
		$toInventoryId = $requestData['to_inventory_id'];
		$products = $requestData['products'];
		$bulkTransfer = $this->createBulkTransfer($fromInventoryId, $toInventoryId, $products);
		$bulkTransferId = $bulkTransfer->id;

		foreach ($products as $product) {

			$productData = Product::find($product['product_id']);

			foreach ($product['product_details'] as $detail) {

				$productDetailId = $productData->quantity_type === Product::PREPACKAGED ? ($detail['product_detail_id'] ?? null) : null;

				$this->bulkTransferInventory($productData, $productDetailId, $detail['stock'], $fromInventoryId, $toInventoryId, $bulkTransferId, $requestData);
			}
		}
		return ['message' => __('message.successTransferred', ['name' => __('product.bulkTransfer')])];
	}

	public function createBulkTransfer(int $fromInventoryId, int $toInventoryId, $products)
	{
		$bulkTransfer = new BulkTransfer();
		$bulkTransfer->from_inventory_id = $fromInventoryId;
		$bulkTransfer->to_inventory_id = $toInventoryId;
		$bulkTransfer->products = $products;
		$bulkTransfer->save();
		return $bulkTransfer;
    }

	public function bulkTransferInventory(Product $product, int $productDetailId = null, int $stock, int $fromInventoryId, int $toInventoryId, int $bulkTransferId, array $requestData)
	{
		list($fromInventory, $fromProductStock) = $this->getInventoryInfo($this->modelType, $fromInventoryId, $productDetailId, $product);

        if ($fromProductStock > 0 && $stock > 0 && $fromProductStock >= $stock) {

	        list($toInventory, $toProductStock) = $this->getInventoryInfo($this->modelType, $toInventoryId, $productDetailId, $product);

        	if ($fromInventory !== null) {
                $this->repository->inventoryUpdate($fromInventory, -$stock, ProductInventory::BULKTRANSFER, $toInventoryId, $toInventory->model->name, $bulkTransferId); 
            }

            $this->repository->inventoryUpdate($toInventory, $stock, ProductInventory::BULKTRANSFER, $fromInventoryId, $fromInventory->model->name ?? Activity::UNALLOCATED, $bulkTransferId);

            dispatch(new ElasticProductJob($product));
        }
	}

	public function getInventoryInfo(string $modelType, int $inventoryId, int $productDetailId = null, Product $product = null, bool $isValidate = false)
	{
		$inventory = null;
		$stock = 0;

		if ($inventoryId === BulkTransfer::UNALLOCATED_ID) {
			$stock = $this->getAvailableStock($product, $modelType, $productDetailId);

		} elseif ($inventoryId !== BulkTransfer::UNALLOCATED_ID) {
			$inventory = $this->repository->getProductInventoryByModel($modelType, [$inventoryId], false, $product->id, $productDetailId);

	        if ($inventory === null && !$isValidate) {
	        	$data = ['productId' => $product->id,'product_detail_id' => $productDetailId,'stock'=> 0];
	        	$inventory = $this->repository->newProductInventory($data, $inventoryId, $modelType);
            }

            $stock = $inventory->stock ?? 0;
        }
        return [$inventory, $stock];
	}

	public function getProducts(array $requestData)
	{
		$fromInventoryId = $requestData['from_inventory_id'];
		$toInventoryId = $requestData['to_inventory_id'];

		if ($fromInventoryId !== BulkTransfer::UNALLOCATED_ID) {
			$products = $this->repository->getProductWithInventoryByModel($this->modelType, $fromInventoryId, ['media']);
		} elseif ($fromInventoryId === BulkTransfer::UNALLOCATED_ID) {
		 	$products = $this->repository->getProductWithInventoryByModel($this->modelType, null, ['media']);
		}

		//Get To Inventory Stocks
		$toProductIds = $products->pluck('id')->unique();
		$toInventories = ProductInventory::ofModelType($this->modelType)->ofModelId($toInventoryId)->whereIn('product_id', $toProductIds)->hasProduct(true)->get();
		return $this->getProductInventories($products, $toInventories, $fromInventoryId);
	}

	public function getProductInventories(object $products, object $toInventories, int $fromInventoryId)
	{
		$productObject = $productIds = [];
		if ($products) {
			$toProductDetails = $toInventories->where('product_detail_id', '>', 0)->pluck('stock', 'product_detail_id')->toArray();
			$toProducts = $toInventories->pluck('stock', 'product_id')->toArray();

			foreach ($products as $product) {
				$productId = $product->id;

				if (!in_array($productId, $productIds)) {
					$stock =  $this->getProductInventoryStock($product, $fromInventoryId);
					$productObject[$productId] = $this->bulkProductFormate($product, null, $stock, $toProducts);
					\array_push($productIds, $productId);
				}

				if($product->quantity_type === Product::PREPACKAGED){
					foreach ($product->productDetails as $detail) {
						$stock =  $this->getProductInventoryStock($detail, $fromInventoryId);
						$variant[$productId][] = $this->bulkProductDetailFormate($detail->id, $stock, $detail->variant->name, $toProductDetails); 
					}
					$productObject[$productId]['product_detail'] = $variant[$productId];
				}
		 	}
		}
		return $productObject;
	}
}
