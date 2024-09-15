<?php

namespace App\Services;

use App\Models\RecordOldSeason;
use Carbon\Carbon;

class RecordOldSeasonService
{
    public function create(array $items): void
    {
        foreach ($items as &$item) {
            $time = Carbon::now();
            $item['created_at'] = $time;
            $item['updated_at'] = $time;
        }

        RecordOldSeason::insert($items);
    }
}
