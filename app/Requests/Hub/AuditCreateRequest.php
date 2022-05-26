<?php

namespace App\Http\Requests\Hub;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Hub\Product;
use App\Models\Hub\ProductInventory;
use App\Http\Traits\ServiceTrait;
use App\Rules\Hub\InventoryModelIdRule;
use App\Models\Repositories\Hub\ProductInventoryRepository;
use App\Http\Traits\AuditTrait;
use App\Http\Traits\ProductInventoryTrait;

class AuditCreateRequest extends FormRequest
{
    use ServiceTrait, AuditTrait, ProductInventoryTrait;

    protected $modelType;

    public function __construct(ProductInventoryRepository $productInventoryRepository)
    {
        $this->modelType = [$this->getProductInventoryModelType(), ProductInventory::CATEGORY];
        $this->productInventoryRepository = $productInventoryRepository;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'model_type' => ['required', Rule::in($this->modelType)],
            'model_id' => ['required', 'numeric', new InventoryModelIdRule($this->model_type)],
            'products' => 'required|array',
            'products.*.product_id' => 'required|numeric',
            'products.*.product_details' => 'required|array',
            'products.*.product_details.*.is_unlimited' => [Rule::in([Product::YES, Product::NO])],
            'products.*.product_details.*.new_stock' => 'required|numeric',
            'products.*.product_details.*.stock' => 'required|numeric',
        ];
        
        return $rules;
    }

    public function messages()
    {
        return [
            'model_type.in' => __('validation.exists', ['attribute' => __('product.modelType')]).__('product.itShouldBe', ['name' => implode(' Or ',$this->modelType)]),
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $productIds = data_get($this->products,'*.product_id');
            $totalStocks = $this->getTotalInventoryStock($this->model_type, $productIds);
            $productObjects = Product::with(['productDetails'])->whereIn('id', $productIds)->get();

            if ($this->products) {
                foreach ($this->products as $keyP => $product) {
                    $productId = $product['product_id'];
                    $productObject = $productObjects->where('id', $productId)->first() ?? null;

                    if(!$productObject) {
                        $validator->errors()->add('products.'.$keyP.'.product_id', __('validation.exists', ['attribute' => __('product.product') .' '. $productId])); 
                    }

                    if($productObject) {
                        $quantityType = $productObject->quantity_type;

                        $productDetailIds = data_get($product['product_details'],'*.product_detail_id');
                        $detailIds = $productObject->productDetails->pluck('id')->toArray();
                        $invalidDetailIds = array_diff($productDetailIds, $detailIds);

                        if($invalidDetailIds && $quantityType === Product::PREPACKAGED){
                            $validator->errors()->add('products.'.$keyP.'.product_details.*.product_detail_id', __('validation.exists', ['attribute' => __('product.productDetail') .' '. implode(', ', $invalidDetailIds)]));
                        }

                        $totalStock =  $totalStocks->where('product_id', $productId);
                        list($keyD, $checkStock) = $this->checkAllocatedStock($product, $quantityType, $totalStock);
                        if (!$checkStock) {
                           $validator->errors()->add('products.'.$keyP.'.product_details.'.$keyD.'.new_stock', __('product.stockAllocatedNotSufficient')); 
                        }
                    }
                }
            }
        });
    }
}
