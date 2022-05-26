<?php

namespace App\Exports;

use App\Models\Repositories\Hub\ProductRepository;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Hub\Product;

class ProductExport implements FromArray, WithHeadings
{
    protected $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function headings(): array
    {
        return [
            'Id',
            'Product Name',
            'Brand',
            'Logo',
            'Category',
            'Quantity Type',
            'Variant',
            'Stock',
			'Price',
            'Wholesale Price'
        ];
    }

    public function array(): array
    {
        return $this->repository->getExportData(Product::CSV);
    }
}