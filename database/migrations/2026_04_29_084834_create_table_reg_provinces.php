<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Database\Seeders\CsvtoArray;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reg_provinces')) {
            Schema::create('reg_provinces', function (Blueprint $table) {
                $table->unsignedBigInteger('id');
                $table->string('name');
            });

            $now = Carbon::now();
            $csv = new CsvtoArray();
            $file = __DIR__.'/csv_indonesia/provinces.csv';
            $header = ['id', 'name'];
            $data = $csv->csv_to_array($file, $header);

            DB::table('reg_provinces')->insertOrIgnore($data);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reg_provinces');
    }
};