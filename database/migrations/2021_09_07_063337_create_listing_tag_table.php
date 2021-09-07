<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('listing_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('listing_id');
            $table->unsignedBigInteger('tag_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('listing_tag');
    }
};
