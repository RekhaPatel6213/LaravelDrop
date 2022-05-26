<?php
namespace App\Imports;

use App\Models\Repositories\Admin\Customer\CustomerRepository;
use App\Models\Repositories\GenericImportRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToCollection, WithHeadingRow
{
    protected $customerRepository;
    protected $importRepository;
    protected $dispensaryId;

    const IMPORT_KEY = 'customer';

    public function __construct(
        CustomerRepository $customerRepository,
        GenericImportRepository $importRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->importRepository = $importRepository;
    }


    public function collection(Collection $rows)
    {
        $index = 1;
        $dispensaryId = $this->dispensaryId;
        $user = auth('admin_api')->user();
        $importData = [];
        Validator::make($rows->toArray(), [
            '*.phone_number_required' => 'required',
        ])->validate();

        foreach ($rows as $row) {
            [$firstName, $lastName] = explode(' ', $row['customer_name_optional'] ?? '');
            $importData[$index] = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => $row['phone_number_required'],
                'email' => $row['email_optional'] ?? '',
                'birth_date' => $row['birthday_optional'] ?? '',
                'address' => $row['address_optional'] ?? '',
                'dispensary_id' => $dispensaryId,
                'is_new' => 1,
            ];

            $customer = $this->customerRepository->findByField('phone', $row['phone_number_required']);

            if ($customer->isNotEmpty()) {
                $importData[$index]['is_new'] = 0;
            }

            $index++;
        }

        $genericData = [
            'import_type' => 'customer',
            'data' => json_encode($importData),
            'dispensary_id' => $dispensaryId,
            'user_id' => $user->id,
            'user_type' => 'admin'
        ];

        $this->importRepository->store($genericData);
    }
}
