<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypesFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types-fields', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('type-id');
            $table->string('name');
            $table->enum('format', ['number', 'text', 'url', 'multiline']);
            $table->string('hint');
            $table->string('placeholder');
            $table->boolean('show-in-list')->default(false);
            $table->boolean('wysiwyg')->default(false);

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('types-fields');
    }
}
