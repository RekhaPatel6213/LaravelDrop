<?php
return [
	'cart' => [
        'preserve_for_user' => true,
        'auto_assign_user' => true,
        'merge_duplicates' => true, 
        'user' => [
	        'model' => App\Models\Admin\Dispensary\DispensaryUser::class,
	    ]
    ],
    'order' => [
        'number' => [
            'generator' => 'time_hash',
            'sequential_number' => [
                'start_sequence_from' => 1,
                'prefix' => 'PO-',
                'pad_length' => 4,
                'pad_string' => '0'
            ],
            'time_hash' => [
                'high_variance' => false, // generates a longer number, use when orders/sec > 20 
                'start_base_date' => '2021-12-15',
                'uppercase' => false
            ]
        ]
    ]
];