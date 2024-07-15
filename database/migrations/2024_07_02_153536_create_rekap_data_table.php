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
        Schema::create('rekap_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rawatInap')->nullable()->constrained('rawat_inap')->onDelete('cascade');
            $table->foreignId('id_titipKunci')->nullable()->constrained('titip_kunci')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_data');
    }
};
