<?php

namespace App\Listeners;

use App\Events\CheckForUpdates;
use Native\Desktop\Facades\AutoUpdater;

class CheckForUpdatesListener { 
    
    public function handle(CheckForUpdates $event): void { 
        AutoUpdater::checkForUpdates();
    }
}