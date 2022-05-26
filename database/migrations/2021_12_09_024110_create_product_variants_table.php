<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Hub\Product;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
            $table->integer('taxonomy_id')->unsigned();
            $table->enum('attribute', [Product::GRAMS, Product::PREPACKAGED, Product::UNITS])->nullable()->default(null);
            $table->enum('type',[Product::YES,Product::NO])->default('YES');
            $table->decimal('quantity', 8, 2)->default(1);
            $table->decimal('limit_quantity', 8, 2)->default(0);
            $table->integer('priority')->default(1);

            $table->foreign('taxonomy_id')->references('id')->on('taxonomies')->onDelete('cascade');
            $table->unique(['taxonomy_id', 'name', 'attribute','type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
}
