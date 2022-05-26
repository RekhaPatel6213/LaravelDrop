<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Repositories\Hub\ProductRepository;
use App\Models\Repositories\GenericImportRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\Hub\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Hub\Category;
use App\Models\Hub\ProductVariant;
use Vanilo\Category\Models\TaxonomyProxy;
use DB;

class ProductImport implements ToCollection, WithHeadingRow
{
    protected $repository, $importRepository;
    const IMPORT_KEY = 'product',
          COMPLETED = 'COMPLETED';

    public function __construct(ProductRepository $repository, GenericImportRepository $importRepository)
    {
        $this->repository = $repository;
        $this->importRepository = $importRepository;
    }

    public function collection(Collection $rows)
    {
        $importData = $productObject = [];
        $categories = data_get($rows, '*.category');
        $categoryList = Category::whereIn('name', $categories)->whereNotNull('parent_id')->get();
        $variantList = ProductVariant::all();
        $totalPrice = 0;

        foreach ($rows as $key => $row) {
            $quantityType = $row['quantity_type'] === Product::UNITS ? '' : $row['quantity_type'];
            $categoryObject = $categoryList->where('name',$row['category'])->where('attribute',$quantityType)->first();
            $parentCategory = $categoryObject->parent->name ?? null;
            $priority = $categoryObject->priority ?? null;

            $row['is_unlimited'] = $row['stock'] === Product::UNLIMITED ? true : false;
            $row['stock'] = explode(',',$row['stock']);
            $row['price'] = explode(',',$row['price']);
            $row['wholesale_price'] = explode(',',$row['wholesale_price']);
            $row['variant'] = explode(',',$row['variant']);
            $row['variant'] = $variantList->where('attribute', $row['quantity_type'])->whereIn('name', $row['variant'])->pluck('name','id');

            $categoryId = $categoryObject->id ?? null;
            $isUnlimited = $row['stock'] === Product::UNLIMITED ? Product::YES : Product::NO;
            $product = $this->repository->checkProductWithFields($categoryId, ['name', 'is_unlimited', 'quantity_type'], [$row['product_name'],$isUnlimited, $row['quantity_type']]);

            $data = $this->repository->importProductFormate($row, $product, $categoryId);
            array_push($importData, $data);
            $productObject[$priority][$parentCategory][] = $data;

            $totalPrice += array_sum(data_get($importData, '*.total'));
        }

        Validator::make($importData, [
            '*.name' => 'required',
            '*.category_id' => 'required',
            '*.quantity_type' => ['nullable', Rule::in(Product::QUANTITY_TYPES)],
            '*.stock' => 'required',
            '*.price' => 'required',
        ])->validate();

        $isNewExist = array_count_values(array_column($importData, 'is_new'));

        $genericData = [
            'import_type' => self::IMPORT_KEY,
            'data' => $productObject,
            'new_items' => $isNewExist[1] ?? 0,
            'existing_items' => $isNewExist[0] ?? 0,
            'total_price' => $totalPrice,
            'user_id' => Auth()->user()->id,
            'user_type' => 'dispensary_user'
        ];
        $this->importRepository->store($genericData);
    }
}
