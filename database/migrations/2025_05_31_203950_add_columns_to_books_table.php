<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->integer('price')->nullable(); // Kolom price bertipe INT
            $table->string('cover_photo')->nullable(); // Kolom cover_photo bertipe STRING
            $table->unsignedBigInteger('genre_id')->nullable(); // Kolom genre_id bertipe unsigned BIG INT
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['price', 'cover_photo', 'genre_id']);
        });
    }
}
