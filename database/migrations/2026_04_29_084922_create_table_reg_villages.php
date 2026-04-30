<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Database\Seeders\CsvtoArray;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reg_villages', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->integer('district_id');
            $table->string('name');
        });

        $now = Carbon::now();
        $csv = new CsvtoArray();
        $resourceFiles = File::allFiles(__DIR__.'/csv_indonesia/villages');
        foreach ($resourceFiles as $file) {
            $header = ['id', 'district_id', 'name'];
            $data = $csv->csv_to_array($file->getRealPath(), $header);

            $collection = collect($data);
            foreach ($collection->chunk(50) as $chunk) {
                DB::table('reg_villages')->insertOrIgnore($chunk->toArray());
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reg_villages');
    }
};