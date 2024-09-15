<?php

namespace App\Services;

use App\Models\Record;
use App\Models\RecordNewSeason;
use App\Models\RecordTemp;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RecordService
{
    public function getRecordsByAreaAndSeason($area, $season, $perPage = 100)
    {
        $where = [
            'area' => $area,
            'season' => $season,
        ];
        return Record::where($where)->paginate($perPage);
    }

    public function getRecordsByAreaAndToon($area, $toon, $perPage = 100)
    {
        $where = [
            'area' => $area,
            'toon' => $toon,
        ];
        return Record::where($where)->paginate($perPage);
    }

    public function searchRecords($area, $toon, $newSeason = '2024 Season 2', $limit = 4)
    {
        return Record::where('season', $newSeason)->where('area', $area)
            ->where('toon', 'like', "{$toon}%")
            ->orderBy('toon', 'asc')->orderBy('id', 'asc')
            ->offset(0)->limit($limit)->get();
    }

    public function storeRecords($items)
    {
        foreach ($items as &$item) {
            $time = Carbon::now();
            $item['created_at'] = $time;
            $item['updated_at'] = $time;
        }

        Record::insert($items);
    }

    public function updateRecords()
    {
        Log::info('update start' . date('Y-m-d H:i:s') . ' ' . __LINE__);
        RecordTemp::truncate();
        $columnStr = implode(', ', $this->getColumns());
        DB::statement("INSERT INTO `record_temps` ({$columnStr}) SELECT {$columnStr} FROM record_old_seasons");
        DB::statement("INSERT INTO `record_temps` ({$columnStr}) SELECT {$columnStr} FROM record_new_seasons");
        RecordTemp::where(['area' => 'GB'])->orWhere(['season' => 'Frontier League'])->delete();
        Schema::rename('record_temps', 'record_temps_bak');
        Schema::rename('records', 'record_temps');
        Schema::rename('record_temps_bak', 'records');
        $this->downloadAvatarImages();
        Log::info('update end' . date('Y-m-d H:i:s') . ' ' . __LINE__);
    }

    private function getColumns()
    {
        $columns = DB::select(DB::raw('SHOW COLUMNS FROM record_temps WHERE `Key` != "PRI"'));
        $columns = array_column($columns, 'Field');
        $columns[] = 'season'; // 파티셔닝하면서 season도 기본키 됨
        return $columns;
    }

    private function downloadAvatarImages()
    {
        $avatars = Record::select('avatar')->groupBy('avatar')->get()->toArray();
        $avatars = array_column($avatars, 'avatar');
        $avatars = array_filter($avatars);
        foreach ($avatars as $imageUrl) {
            $filename = pathinfo($imageUrl, PATHINFO_BASENAME);
            if (File::exists(storage_path("app/public/images/$filename"))) {
                continue;
            }
            $response = Http::get($imageUrl);
            if ($response->successful()) {
                $imageData = $response->body();
                Storage::disk('public')->put("images/$filename", $imageData);
            }
            sleep(1);
        }
    }

    public function countRecords($season)
    {
        $where = [
            'season' => $season,
        ];
        return Record::withTrashed()->where($where)->count();
    }
}
