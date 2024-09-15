<?php

namespace App\Http\Controllers;

use App\Services\RecordTempService;
use App\Http\Middleware\BlockIpMiddleware;
/*
record_new_seasons, record_old_seasons 2개 테이블 합쳐서
record_temps에 넣어놓고
records과 swap
*/

class RecordTempController extends Controller
{
    protected $recordTempService;

    public function __construct(RecordTempService $recordTempService)
    {
        $this->middleware(BlockIpMiddleware::class);
        $this->recordTempService = $recordTempService;
    }

    public function emptyTable()
    {
        $this->recordTempService->emptyTable();

        return response()->json(['message' => 'emptyTable'], 200);
    }

    public function insertRecordOldSeasons()
    {
        $this->recordTempService->insertRecordOldSeasons();

        return response()->json(['message' => 'insertRecordOldSeasons'], 200);
    }

    public function insertRecordNewSeasons()
    {
        try {
            $this->recordTempService->insertRecordNewSeasons();

            return response()->json(['message' => 'insertRecordNewSeasons'], 200);
        } catch (\Exception $e) {
            return  response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroyUnusedData()
    {
        $this->recordTempService->destroyUnusedData();

        return response()->json(['message' => 'destroyUnusedData'], 200);
    }

    public function replaceTable()
    {
        $this->recordTempService->replaceTable();

        return response()->json(['message' => 'replaceTable'], 200);
    }

    public function downAvatarImage()
    {
        $this->recordTempService->downAvatarImage();

        return response()->json(['message' => 'downAvatarImage'], 200);
    }
}
