<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributorsTable extends Migration
{
    public function up()
    {
        Schema::create('distributors', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->boolean('updating')->default(false);
            $table->dateTime('last_updated')->nullable();
            $table->string('products_class');
            $table->unsignedBigInteger('total_products')->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('distributors');
    }
}
