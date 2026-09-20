<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDokumenToSiswasAndMahasiswas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('surat_permohonan')->nullable();
            $table->string('laporan_magang')->nullable();
        });
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('surat_permohonan')->nullable();
            $table->string('laporan_magang')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn(['surat_permohonan', 'laporan_magang']);
        });
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn(['surat_permohonan', 'laporan_magang']);
        });
    }
}
