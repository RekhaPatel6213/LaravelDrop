<?php

namespace Database\Seeders;

use App\Models\Location\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = [
            [
                'zip_code' => 99546,
                'city' => 'Adak',
                'state' => 'Alaska',
                'short_state' => 'AK',
                'country' => 'Aleutians West (CA)',
                'country_code' => 'US',
                'lat' => '51.88000000',
                'lng' => '-176.65810000',
            ],
            [
                'zip_code' => 72001,
                'city' => 'Adona',
                'state' => 'Arkansas',
                'short_state' => 'AR',
                'country' => 'Perry',
                'country_code' => 'US',
                'lat' => '35.04700000',
                'lng' => '-92.90330000',
            ],
            [
                'zip_code' => 85320,
                'city' => 'Aguila',
                'state' => 'Arizona',
                'short_state' => 'AZ',
                'country' => 'Maricopa',
                'country_code' => 'US',
                'lat' => '33.91860000',
                'lng' => '-113.21340000',
            ],

        ];
        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
