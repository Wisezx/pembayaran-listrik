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
         Schema::create('pembayarans', function (Blueprint $table) {
             $table->id();
             $table->unsignedBigInteger('pelanggan_id');
             $table->unsignedBigInteger('petugas_id');
             $table->dateTime('tanggal');
             $table->string('bulan_tagihan', 20);
             $table->decimal('jumlah_tagihan', 10, 2);
             $table->decimal('jumlah_bayar', 10, 2);
             $table->string('metode_pembayaran', 50);
             $table->text('catatan')->nullable();
             $table->dateTime('tanggal_bayar')->nullable();
             $table->timestamps();

             // Foreign key constraints
             $table->foreign('pelanggan_id')->references('id')->on('pelanggans')->onDelete('cascade');
             $table->foreign('petugas_id')->references('id')->on('users')->onDelete('cascade');
         });
     }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
