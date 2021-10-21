<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdatedProductsToDistributors extends Migration
{
    public function up()
    {
        Schema::table('distributors', function (Blueprint $table) {
            $table->unsignedBigInteger('updated_products')->default(0)->after('total_products');
        });
    }
}
