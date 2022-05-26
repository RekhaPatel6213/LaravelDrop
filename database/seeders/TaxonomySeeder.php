<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Vanilo\Category\Models\Taxonomy;
use Vanilo\Category\Models\Taxon;

class TaxonomySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $taxonomies = config('admin_setting.taxonomies');
        foreach ($taxonomies as $taxonomy) {
            Taxonomy::create(['name' => $taxonomy]);
        }
    }
}
