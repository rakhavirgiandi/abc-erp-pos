<?php

namespace App\Console\Commands;

use App\Helpers\ModelHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class ReassignUserPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adjust:userpermission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adjust Admin Permission';

    /**
     * Create a new command instance.
     *
     * @return void
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
        // ModelHelper::reorderPermissionAdmin();
        ModelHelper::reorderPermissionAdmin();
    }
}
