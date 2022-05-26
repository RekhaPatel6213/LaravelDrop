<?php
    return [
        'sms_groups' =>[
            [
                'name' => 'Gift SMS',
                'type' => 'GIFT',
                'months' => ''
            ],
            [
                'name' => 'One time Purchase',
                'type' => 'ONETIME',
                'months' => 0
            ],
            [
                'name' => '3 Months Recurring',
                'type' => 'RECURRING',
                'months' => 3
            ],
            [
                'name' => '6 Months Recurring',
                'type' => 'RECURRING',
                'months' => 6
            ],
            [
                'name' => '12 Months Recurring',
                'type' => 'RECURRING',
                'months' => 12
            ],
        ],
        'taxonomies' => [
            'Flower',
            'Pre-Packaged',
            'Unit'
        ],
        'taxon_consts' => [
            'INACTIVE' => 'INACTIVE',
            'ACTIVE' => 'ACTIVE',
            'GRAMS' => 'GRAMS',
            'PREPACKAGED' => 'PRE-PACKAGED',
            'UNITS' => 'UNITS'
        ],
        'category_attributes' => [
            'Grams',
            'Pre-Packaged',
            'Units'
        ],
    ];