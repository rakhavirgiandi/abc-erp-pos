<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $from    = $request->filled('datetime_from') ? Carbon::parse($request->datetime_from) : now()->startOfDay();
        $to      = $request->filled('datetime_to') ? Carbon::parse($request->datetime_to) : now();
        $names   = $request->filled('name') ? explode(',', $request->name) : null;
        $levels  = $request->filled('level') ? array_map('strtoupper', explode(',', $request->level)) : null;
        $search  = $request->input('search');
        $perPage = min(max((int) $request->query('per_page', 10), 1), 100);
        $page    = max((int) $request->query('page', 1), 1);
        $offset  = ($page - 1) * $perPage;

        $total = 0;
        $data = [];
        $logNames = [];

        for ($d = $to->copy()->startOfDay(); $d >= $from->copy()->startOfDay(); $d->subDay()) {
            $path = storage_path('logs/system-' . $d->format('Y-m-d') . '.jsonl');
            if (! is_file($path)) {
                continue;
            }

            foreach (array_reverse(file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES), true) as $no => $line) {
                $l = json_decode($line, true);
                if (! $l || ! Carbon::parse($l['datetime'])->between($from, $to)) {
                    continue;
                }
                if ($search && stripos($l['message'], $search) === false) {
                    continue;
                }
                if ($levels && ! in_array($l['level_name'], $levels)) {
                    continue;
                }

                $type = $l['context']['type'] ?? null;
                if ($type) {
                    $logNames[$type] = true;
                }
                if ($names && ! in_array($type, $names)) {
                    continue;
                }

                if ($total >= $offset && count($data) < $perPage) {
                    $data[] = [
                        'id'          => $d->format('Y-m-d') . ':' . $no,
                        'log_name'    => $type,
                        'description' => $l['message'],
                        'level'       => strtolower($l['level_name']),
                        'created_at'  => $l['datetime'],
                        'properties'  => $l['context'],
                    ];  
                }
                $total++;
            }
        }

        return response()->json([
            'meta' => [
                'per_page'     => $perPage,
                'total'        => $total,
                'current_page' => $page,
                'last_page'    => max(1, (int) ceil($total / $perPage)),
            ],
            'data'      => $data,
            'log_names' => array_keys($logNames),
        ]);
    }

    public function show($id)
    {
        abort_unless(preg_match('/^(\d{4}-\d{2}-\d{2}):(\d+)$/', $id, $m), 404);
    
        $path = storage_path("logs/system-{$m[1]}.jsonl");
        abort_unless(is_file($path), 404);
    
        $file = new \SplFileObject($path);
        $file->seek((int) $m[2]);
    
        $l = json_decode(trim($file->current()), true);
        abort_unless($l, 404);
    
        return response()->json([
            'id'          => $id,
            'log_name'    => $l['context']['type'] ?? null,
            'description' => $l['message'],
            'level'       => strtolower($l['level_name']),
            'created_at'  => $l['datetime'],
            'properties'  => $l['context'],
        ]);
    }
}