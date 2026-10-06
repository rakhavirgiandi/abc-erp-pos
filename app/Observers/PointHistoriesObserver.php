<?php

namespace App\Observers;

use App\Models\Companies\v1\PointHistories;
use Illuminate\Support\Facades\Log;

class PointHistoriesObserver
{
    public function created(PointHistories $p): void
    {
        $this->log('create', $p);
    }

    public function forceDeleted(PointHistories $p): void
    {
        $this->log('delete', $p);
    }

    protected function log(string $action, PointHistories $p): void
    {
        Log::channel('json')->info("Point history {$action}", [
            'type'             => 'point_history',
            'action'           => $action,
            'point_history_id' => $p->id,
            'model'            => $p->model,
            'model_id'         => $p->model_id,
            'contact_id'       => $p->contact_id,
            'point'            => $p->point,
            'point_type'       => $p->type,
            'user_id'          => auth()->id(),
        ]);
    }
}