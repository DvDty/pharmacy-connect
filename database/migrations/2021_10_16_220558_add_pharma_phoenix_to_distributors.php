<?php

use App\Models\Distributor;
use Illuminate\Database\Migrations\Migration;

class AddPharmaPhoenixToDistributors extends Migration
{
    public function up()
    {
        Distributor::create([
            'name'           => 'Phoenix Pharma',
            'products_class' => '\App\Models\PhoenixPharmaProduct',
        ]);
    }
}
