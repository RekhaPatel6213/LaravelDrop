<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\hub\ProductVariant;
use Carbon\Carbon;

class productVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currentTimestamp = Carbon::now()->toDateTimeString();

        $variants = [
            [   
                'name' => '.5G',
                'type' => 'YES',
                'priority' => 1,
                'taxonomy_id' => 1,
                'attribute' => 'GRAMS',
                'quantity' => '0.50',
                'limit_quantity' => '0.50'
            ],
            [   
                'name' => '1G',
                'type' => 'YES',
                'priority' => 2,
                'taxonomy_id' => 1,
                'attribute' => 'GRAMS',
                'quantity' => '1',
                'limit_quantity' => '1'

            ],
            [   
                'name' => '2G',
                'type' => 'YES',
                'priority' => 3,
                'taxonomy_id' => 1,
                'attribute' => 'GRAMS',
                'quantity' => '2',
                'limit_quantity' => '2'

            ],
            [   
                'name' => '1/8',
                'type' => 'NO',
                'priority' => 4,
                'taxonomy_id' => 1,
                'attribute' => 'GRAMS',
                'quantity' => '1',
                'limit_quantity' => '3.5'

            ],
            [   
                'name' => '4G',
                'type' => 'YES',
                'priority' => 5,
                'taxonomy_id' => 1,
                'attribute' => 'GRAMS',
                'quantity' => '4',
                'limit_quantity' => '4'

            ],
            [   
                'name' => '5G',
                'type' => 'YES',
                'priority' => 6,
                'taxonomy_id' => 1,
                'attribute' => 'GRAMS',
                'quantity' => '5',
                'limit_quantity' => '5'

            ],
            [   
                'name' => '1/4',
                'type' => 'NO',
                'priority' => 7,
                'taxonomy_id' => 1,
                'attribute' => 'GRAMS',
                'quantity' => '2',
                'limit_quantity' => '7'

            ],
            [   
                'name' => '10G',
                'type' => 'YES',
                'priority' => '8',
                'taxonomy_id' => 1,
                'attribute' => 'GRAMS',
                'quantity' => '10',
                'limit_quantity' => '10'

            ],
            [   
                'name' => '1/2',
                'type' => 'NO',
                'priority' => 9,
                'taxonomy_id' => 1,
                'attribute' => 'GRAMS',
                'quantity' => '4',
                'limit_quantity' => '14'

            ],
            [   
                'name' => 'OZ.',
                'type' => 'NO',
                'priority' => 10,
                'taxonomy_id' => 1,
                'attribute' => 'GRAMS',
                'quantity' => '8',
                'limit_quantity' => '28'

            ],
            /*[   
                'name' => '.5G',
                'type' => 'YES',
                'priority' => 1,
                'taxonomy_id' => 1,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => 1,
                'limit_quantity' => '0.50'
            ],
            [   
                'name' => '1G',
                'type' => 'YES',
                'priority' => 2,
                'taxonomy_id' => 1,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => 1,
                'limit_quantity' => '1'

            ],
            [   
                'name' => '2G',
                'type' => 'YES',
                'priority' => 3,
                'taxonomy_id' => 1,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => 1,
                'limit_quantity' => '2'

            ],
            [   
                'name' => '1/8',
                'type' => 'YES',
                'priority' => 4,
                'taxonomy_id' => 1,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => 1,
                'limit_quantity' => '3.5'

            ],
            [   
                'name' => '4G',
                'type' => 'YES',
                'priority' => 5,
                'taxonomy_id' => 1,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => 1,
                'limit_quantity' => '4'

            ],
            [   
                'name' => '5G',
                'type' => 'YES',
                'priority' => 6,
                'taxonomy_id' => 1,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => 1,
                'limit_quantity' => '5'

            ],
            [   
                'name' => '1/4',
                'type' => 'YES',
                'priority' => 7,
                'taxonomy_id' => 1,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => 1,
                'limit_quantity' => '7'

            ],
            [   
                'name' => '10G',
                'type' => 'YES',
                'priority' => '8',
                'taxonomy_id' => 1,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => 1,
                'limit_quantity' => '10'

            ],
            [   
                'name' => '1/2',
                'type' => 'YES',
                'priority' => 9,
                'taxonomy_id' => 1,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => 1,
                'limit_quantity' => '14'

            ],
            [   
                'name' => 'OZ.',
                'type' => 'YES',
                'priority' => 10,
                'taxonomy_id' => 1,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => 1,
                'limit_quantity' => '28'

            ]*/
            [   
                'name' => '.5G',
                'type' => 'YES',
                'priority' => '1',
                'taxonomy_id' => 2,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => '0.50',
                'limit_quantity' => '0.50'

            ],
            [   
                'name' => '1G',
                'type' => 'YES',
                'priority' => 2,
                'taxonomy_id' => 2,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => '1',
                'limit_quantity' => '1'

            ],
            [   
                'name' => '2G',
                'type' => 'YES',
                'priority' => 3,
                'taxonomy_id' => 2,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => '2',
                'limit_quantity' => '2'

            ],
            [   
                'name' => '1/8',
                'type' => 'YES',
                'priority' => 4,
                'taxonomy_id' => 2,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => '1',
                'limit_quantity' => '3.5'

            ],
            [   
                'name' => '3G',
                'type' => 'YES',
                'priority' => 5,
                'taxonomy_id' => 2,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => '1',
                'limit_quantity' => '3'

            ],
            [   
                'name' => '4G',
                'type' => 'YES',
                'priority' => 6,
                'taxonomy_id' => 2,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => '1',
                'limit_quantity' => '4'

            ],
            [   
                'name' => '5G',
                'type' => 'YES',
                'priority' => 7,
                'taxonomy_id' => 2,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => '1',
                'limit_quantity' => '5'

            ],
            [   
                'name' => '12G',
                'type' => 'YES',
                'priority' => 8,
                'taxonomy_id' => 2,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => '12',
                'limit_quantity' => '12'

            ],
            [   
                'name' => 'OZ.',
                'type' => 'YES',
                'priority' => 9,
                'taxonomy_id' => 2,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => '1',
                'limit_quantity' => '28'

            ],
            [   
                'name' => '300MG',
                'type' => 'YES',
                'priority' => 10,
                'taxonomy_id' => 2,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => '1',
                'limit_quantity' => '0.30'

            ],
            [   
                'name' => '700MG',
                'type' => 'YES',
                'priority' => 11,
                'taxonomy_id' => 2,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => '1',
                'limit_quantity' => '0.70'

            ],
            [   
                'name' => '750MG',
                'type' => 'YES',
                'priority' => 12,
                'taxonomy_id' => 2,
                'attribute' => 'PRE-PACKAGED',
                'quantity' => '1',
                'limit_quantity' => '0.75'

            ]
        ];

        ProductVariant::insert($variants);
        
    }
}
