<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RecordNewSeasonService;
use App\Http\Middleware\BlockIpMiddleware;

class RecordNewSeasonController extends Controller
{
    protected $recordNewSeasonService;

    public function __construct(RecordNewSeasonService $recordNewSeasonService)
    {
        $this->middleware(BlockIpMiddleware::class);
        $this->recordNewSeasonService = $recordNewSeasonService;
    }

    public function store(Request $request)
    {
        $requ = $request->input();
        $items = $requ['items'];

        $this->recordNewSeasonService->create($items);

        return response()->json([
            'message' => 'Resource created successfully.'
        ], 201);
    }

    public function destroy()
    {
        $this->recordNewSeasonService->delete();

        return response()->json(['message' => ''], 200);
    }

    public function count()
    {
        $count = $this->recordNewSeasonService->count();

        return response()->json([
            'count' => $count,
        ]);
    }
}
