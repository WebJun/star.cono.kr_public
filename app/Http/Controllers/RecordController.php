<?php

namespace App\Http\Controllers;

use App\Services\RecordService;
use Illuminate\Http\Request;
use App\Http\Middleware\BlockIpMiddleware;

class RecordController extends Controller
{
    protected $recordService;

    public function __construct(RecordService $recordService)
    {
        $this->recordService = $recordService;
        $this->middleware(BlockIpMiddleware::class)->only(['store', 'update', 'count']);
    }

    public function index(Request $request)
    {
        $areasStr = implode(',', getAreas());
        $seasonsStr = implode(',', getSeasons());
        $validatedData = $request->validate([
            'area' => "required|in:{$areasStr}",
            'season' => "required|in:{$seasonsStr}",
        ]);

        $records = $this->recordService->getRecordsByAreaAndSeason($validatedData['area'], $validatedData['season']);

        return response()->json($records);
    }

    public function show($area, $toon)
    {
        if (in_array($area, getAreas()) === false) {
            return response()->json(['error' => 'Invalid area'], 400);
        }
        if (mb_strlen($toon) === 0) {
            return response()->json(['error' => 'Invalid id'], 400);
        }

        $records = $this->recordService->getRecordsByAreaAndToon($area, $toon);

        return response()->json($records);
    }

    public function search($area, $toon)
    {
        if (in_array($area, getAreas()) === false) {
            return response()->json(['error' => 'Invalid area'], 400);
        }
        if (mb_strlen($toon) === 0) {
            return response()->json(['error' => 'Invalid id'], 400);
        }

        $records = $this->recordService->searchRecords($area, $toon);

        return response()->json($records);
    }

    public function store(Request $request)
    {
        $requ = $request->input();
        $items = $requ['items'];

        $this->recordService->storeRecords($items);

        return response()->json([
            'message' => 'Resource created successfully.'
        ], 201);
    }

    public function update()
    {
        try {
            $this->recordService->updateRecords();
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
        return response()->json(['message' => 'changeTable'], 200);
    }

    public function count(Request $request)
    {
        $requ = $request->input();
        $count = $this->recordService->countRecords($requ['season']);

        return response()->json(['count' => $count], 200);
    }
}
