<?php

namespace App\Http\Controllers;

use App\Services\StatisticsService;

class StatisticController extends Controller
{
    public function __invoke(StatisticsService $stats)
    {
        return response()->json([ 'statistics' => $stats->getStats() ]);
    }
}