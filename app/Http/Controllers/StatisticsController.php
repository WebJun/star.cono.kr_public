<?php

namespace App\Http\Controllers;

use App\Services\StatisticsService;

class StatisticsController extends Controller
{
    protected $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function numKind()
    {
        $kinds = $this->statisticsService->getKindCounts();
        return response()->json($kinds);
    }

    public function ability_kind_ajax()
    {
        $result = $this->statisticsService->getAbilityKindDistribution();
        return response()->json($result);
    }
}
