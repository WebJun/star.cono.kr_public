<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RecordOldSeasonService;
use App\Http\Middleware\BlockIpMiddleware;

class RecordOldSeasonController extends Controller
{
    protected $recordOldSeasonService;

    public function __construct(RecordOldSeasonService $recordOldSeasonService)
    {
        $this->middleware(BlockIpMiddleware::class);
        $this->recordOldSeasonService = $recordOldSeasonService;
    }

    public function store(Request $request)
    {
        $requ = $request->input();
        $items = $requ['items'];

        $this->recordOldSeasonService->create($items);

        return response()->json([
            'message' => 'Resource created successfully.'
        ], 201);
    }
}
