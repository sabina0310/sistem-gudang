<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mutasi', function (Blueprint $table) {
            $table->id();
            $table->integer('barang_id');
            $table->foreign('barang_id')->references('id')->on('barang');
            $table->date('tanggal');
            $table->enum('jenis_mutasi', ['masuk', 'keluar']);
            $table->integer('jumlah');
            $table->timestamps();
            $table->integer('created_by')->default(0);
            $table->foreign('created_by')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi');
    }
};
