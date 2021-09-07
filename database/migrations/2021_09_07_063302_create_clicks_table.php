<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clicks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('listing_id');
            $table->text('user_agent')->nullable();
            $table->string('ip')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clicks');
    }
};
