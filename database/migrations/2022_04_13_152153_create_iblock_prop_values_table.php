<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIblockPropValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iblock_prop_values', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('value');
            $table->unsignedBigInteger('prop_id');
            $table->foreign('prop_id')
                ->references('id')->on('iblock_properties')
                ->onDelete('cascade');
            $table->unsignedBigInteger('el_id');
            $table->foreign('el_id')
                ->references('id')->on('iblock_elements')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('iblock_prop_values');
    }
}
