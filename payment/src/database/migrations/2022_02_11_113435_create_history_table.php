<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatehistoryTable extends Migration
{
    public function up()
    {
        Schema::create('chonjopayhistory', function (Blueprint $table) {
            $table->id();
            $table->chonjopay();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chonjopayhistory');
    }
}
