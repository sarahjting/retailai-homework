<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index(); // we'll likely be sorting by name
            $table->string('sku')->index(); // non-unique because of the soft deletes
            $table->string('image_extension');
            $table->string('image_disk');

            $table->text('description');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('products');
    }
};
