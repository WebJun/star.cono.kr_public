<?php

namespace App\Services;

use App\Models\RecordNewSeason;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RecordNewSeasonService
{
    public function create(array $items): void
    {
        foreach ($items as &$item) {
            $time = Carbon::now();
            $item['created_at'] = $time;
            $item['updated_at'] = $time;
        }

        RecordNewSeason::insert($items);
    }

    public function delete(): void
    {
        DB::table('record_new_seasons')->truncate(); // 하드 삭제
    }

    public function count(): int
    {
        return RecordNewSeason::count();
    }
}
