<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProspectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->dateTime('birthday');
            $table->string('email');
            $table->string('phone');
            $table->integer('region_id')->unsigned()->nullable();
            $table->integer('unity_id')->unsigned()->nullable();
            $table->integer('total_score');
            $table->boolean('is_sync')->default(false);
            $table->timestamps();
            
            $table->foreign('region_id')->references('id')->on('regions'); 
            $table->foreign('unity_id')->references('id')->on('unities'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('prospects');
    }
}
