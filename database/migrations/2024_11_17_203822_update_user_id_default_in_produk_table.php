<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change(); // Membuat user_id nullable
        });
    }

    public function down()
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change(); // Kembali ke NOT NULL
        });
    }
};
