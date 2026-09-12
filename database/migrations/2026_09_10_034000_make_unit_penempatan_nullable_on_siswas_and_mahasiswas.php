<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MakeUnitPenempatanNullableOnSiswasAndMahasiswas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE siswas MODIFY unit_penempatan VARCHAR(255) NULL;');
        DB::statement('ALTER TABLE mahasiswas MODIFY unit_penempatan VARCHAR(255) NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE siswas MODIFY unit_penempatan VARCHAR(255) NOT NULL;');
        DB::statement('ALTER TABLE mahasiswas MODIFY unit_penempatan VARCHAR(255) NOT NULL;');
    }
}
