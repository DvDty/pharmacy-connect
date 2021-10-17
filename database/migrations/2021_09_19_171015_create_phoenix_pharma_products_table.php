<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhoenixPharmaProductsTable extends Migration
{
    public function up()
    {
        Schema::create('phoenix_pharma_products', function (Blueprint $table) {
            $table->id();

            $table->integer('articleId');
            $table->integer('articleNumber');
            $table->string('cyrName');
            $table->string('latName');
            $table->string('measureName');
            $table->string('producerCode');
            $table->string('producerName');
            $table->float('basePrice');
            $table->float('salePrice');
            $table->float('maxPrice');
            $table->string('nhifCode');
            $table->float('nhifBasePrice');
            $table->float('nhifSalePrice');
            $table->float('nhifMaxPrice');
            $table->boolean('isMedicalPrescription');
            $table->boolean('isWebSaleProhibition');
            $table->boolean('isDrugstoreAllowed');
            $table->boolean('isDrug');
            $table->boolean('isForRefrigerator');
            $table->text('advertismentText');
            $table->string('barcode1');
            $table->string('barcode2');
            $table->text('description');
            $table->integer('overrateGroupID');
            $table->dateTime('lastupdate');
            $table->boolean('isActive');
            $table->boolean('deleted');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('phoenix_pharma_products');
    }
}
