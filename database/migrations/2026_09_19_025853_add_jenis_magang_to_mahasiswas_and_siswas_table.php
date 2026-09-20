<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJenisMagangToMahasiswasAndSiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->enum('jenis_magang', ['Reguler', 'Berbayar'])->default('Reguler')->after('jenis_kelamin');
        });

        Schema::table('siswas', function (Blueprint $table) {
            $table->enum('jenis_magang', ['Reguler', 'Berbayar'])->default('Reguler')->after('jenis_kelamin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn('jenis_magang');
        });

        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn('jenis_magang');
        });
    }
}
