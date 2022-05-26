<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Hub\Product;

class AddVaniloProductColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('dispensary_id')->unsigned();
            $table->string('brand', 100)->nullable();
            $table->enum('strain_type', [Product::INDICA, Product::SATIVA, Product::HYBRID, Product::CBDHIGH])->nullable();
            $table->enum('product_type', [Product::RECREATIONAL,Product::MEDICAL])->nullable();
            $table->enum('quantity_type',[Product::UNITS,Product::GRAMS, Product::PREPACKAGED])->default(Product::UNITS);
            $table->enum('is_unlimited',[Product::YES,Product::NO])->default(Product::NO);
            $table->enum('is_featured',[Product::YES,Product::NO])->default(Product::NO);
            $table->decimal('thc', 3, 2)->nullable();
            $table->enum('thc_type', [Product::PERCENT,Product::MG])->nullable()->default(null);
            $table->decimal('cbd', 3, 2)->nullable();
            $table->enum('cbd_type', [Product::PERCENT,Product::MG])->nullable()->default(null);
            $table->decimal('cbn', 3, 2)->nullable();
            $table->enum('cbn_type', [Product::PERCENT,Product::MG])->nullable()->default(null);
            $table->enum('type',[Product::YES,Product::NO])->default('NO');
            $table->integer('priority')->nullable();
            $table->decimal('wholesale_price', 12, 2)->nullable();
            
            $table->foreign('dispensary_id')->references('id')->on('dispensaries')->onDelete('cascade');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'price'
            ]);
        });

        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->unsigned();
            $table->integer('variant_id')->unsigned()->nullable();
            $table->decimal('wholesale_price', 12, 2)->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->integer('stock')->nullable()->default(null);
            $table->integer('original_stock')->nullable()->default(null);
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'dispensary_id',
                'brand',
                'strain_type',
                'product_type',
                'quantity_type',
                'is_unlimited',
                'is_featured',
                'thc',
                'thc_type',
                'cbd',
                'cbd_type',
                'cbn',
                'cbn_type',
                'type',
                'priority',
                'wholesale_price'
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 15, 4)->nullable();
        });

        Schema::dropIfExists('product_details');
    }
}
