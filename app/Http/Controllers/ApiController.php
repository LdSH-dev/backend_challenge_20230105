<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function index()
    {
        $databaseConnection = DB::connection()->getPdo() ? 'OK' : 'Failed';

        $lastCronRun = Cache::get(env('IMPORT_CRON_LASTRUN_CACHE_KEY'));

        $uptime = shell_exec('uptime -p');

        $memoryUsage = memory_get_usage(true);

        return response()->json([
            'database_connection' => $databaseConnection,
            'last_cron_run' => $lastCronRun,
            'uptime' => $uptime,
            'memory_usage' => $memoryUsage,
        ]);
    }
}
