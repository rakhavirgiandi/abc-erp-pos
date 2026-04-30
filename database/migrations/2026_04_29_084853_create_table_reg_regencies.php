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
        Schema::create('reg_regencies', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->integer('province_id');
            $table->string('name');
        });

        $now = Carbon::now();
        $csv = new CsvtoArray();
        $file = __DIR__.'/csv_indonesia/cities.csv';
        $header = ['id', 'province_id', 'name'];
        $data = $csv->csv_to_array($file, $header);

        $collection = collect($data);
        foreach ($collection->chunk(50) as $chunk) {
            DB::table('reg_regencies')->insertOrIgnore($chunk->toArray());
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reg_regencies');
    }
};