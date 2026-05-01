<?php

namespace App\Console\Commands;

use App\Helpers\ModelHelper;
use Illuminate\Console\Command;

class AdjustSequence extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:adjust-sequence';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adjust Sequence';

    /**
     * Execute the console command.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ModelHelper::adjustSequencePostgreSql();
    }
}
