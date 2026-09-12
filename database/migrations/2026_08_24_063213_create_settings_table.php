<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_instansi')->default('BPK RI Perwakilan Provinsi Jambi');
            
            // Penandatangan Laporan (Humas)
            $table->string('jabatan_pejabat')->nullable();
            $table->string('nama_pejabat')->nullable();
            $table->string('nip_pejabat')->nullable();
            
            // Penandatangan Sertifikat (Kepala Sekretariat / Perwakilan)
            $table->string('jabatan_pejabat_sertifikat')->nullable(); 
            $table->string('nama_pejabat_sertifikat')->nullable();    
            $table->string('nip_pejabat_sertifikat')->nullable();     
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
