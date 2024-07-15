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
        Schema::create('titip_kunci', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tanggal_masuk')->useCurrent();
            $table->string('no_pol');
            $table->string('nama_petugas');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titip_kunci');
    }
};
