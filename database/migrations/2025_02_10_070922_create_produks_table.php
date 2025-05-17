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
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk')->unique();
            $table->string('nama');
            $table->decimal('harga', 10, 2); // Harga dengan 2 digit desimal
            $table->integer('stok')->default(0); // Jumlah stok produk
            $table->text('deskripsi')->nullable(); // Deskripsi produk (opsional)
            $table->string('gambar')->nullable(); // Nama file gambar (opsional)
            $table->string('game')->nullable(); // Nama file game yang diupload, misal 1747376892_6826dafc2a1fe.zip
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
