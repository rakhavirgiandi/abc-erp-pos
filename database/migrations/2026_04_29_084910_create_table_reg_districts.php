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
        if (!Schema::hasTable('reg_districts')) {
            Schema::create('reg_districts', function (Blueprint $table) {
                $table->unsignedBigInteger('id');
                $table->integer('regency_id');
                $table->string('name');
            });

            $now = Carbon::now();
            $csv = new CsvtoArray();
            $file = __DIR__.'/csv_indonesia/districts.csv';
            $header = ['id', 'regency_id', 'name'];
            $data = $csv->csv_to_array($file, $header);

            $collection = collect($data);
            foreach ($collection->chunk(50) as $chunk) {
                DB::table('reg_districts')->insertOrIgnore($chunk->toArray());
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reg_districts');
    }
};