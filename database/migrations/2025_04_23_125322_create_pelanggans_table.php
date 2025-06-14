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
    Schema::create('pelanggans', function (Blueprint $table) {
        $table->id();
        $table->string('NoKontrol')->unique();
        $table->string('Nama');
        $table->string('Alamat');
        $table->string('NoTelp');
        $table->string('Jenis_Plg');

        // Tambahkan foreign key manual ke jenis_plg di tarifs
        $table->foreign('Jenis_Plg')->references('Jenis_Plg')->on('tarifs')->onDelete('cascade');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_pelanggans');
    }
};
