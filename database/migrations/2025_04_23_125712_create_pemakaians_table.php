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
        Schema::create('pemakaians', function (Blueprint $table) {
            $table->id(); // ID unik untuk tiap catatan pemakaian
            $table->integer('tahun');
            $table->integer('bulan');
            $table->string('NoKontrol');
            $table->integer('meterawal');
            $table->integer('meterakhir');
            $table->integer('jumlahpakai');
            $table->decimal('biayabebanpemakai', 10, 2);
            $table->decimal('biayapemakaian', 10, 2);
            $table->enum('status', ['Lunas', 'Belum Bayar', 'Sudah Bayar']); // Added 'Sudah Bayar' to match form
            $table->decimal('jumlahbayar', 10, 2)->nullable();
            $table->timestamps();

            // Definisi foreign key
            $table->foreign('NoKontrol')->references('NoKontrol')->on('pelanggans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemakaians');
    }
};
