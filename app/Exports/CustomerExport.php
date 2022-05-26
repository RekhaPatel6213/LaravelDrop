<?php

namespace App\Exports;

use App\Models\Repositories\Admin\Customer\DispensaryCustomerRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromCollection, WithHeadings
{
    protected $repository;

    public function __construct(DispensaryCustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function headings(): array
    {
        return [
            'Id',
            'Customer name',
            'Email',
            'Phone number',
            'Status',
        ];
    }

    public function collection()
    {
        return $this->repository->getExportCustomersData();
    }
}
