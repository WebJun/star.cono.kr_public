<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Record;
use App\Models\RecordTemp;
use App\Models\RecordNewSeason;


class RecordTempService
{
    public function emptyTable(): void
    {
        RecordTemp::truncate();
    }

    public function insertRecordOldSeasons(): void
    {
        $columns = DB::select(DB::raw('SHOW COLUMNS FROM record_temps WHERE `Key` != "PRI"'));
        $columnStr = implode(', ', array_column($columns, 'Field'));
        DB::statement("INSERT INTO `record_temps` ({$columnStr}) SELECT {$columnStr} FROM record_old_seasons");
    }

    public function insertRecordNewSeasons(): void
    {
        $minRecord = 0;
        $count = RecordNewSeason::count();
        if ($count < $minRecord) {
            throw new \Exception("{$minRecord} 레코드가 안됨.");
        }
        $columns = DB::select(DB::raw('SHOW COLUMNS FROM record_temps WHERE `Key` != "PRI"'));
        $columnStr = implode(', ', array_column($columns, 'Field'));
        DB::statement("INSERT INTO `record_temps` ({$columnStr}) SELECT {$columnStr} FROM record_new_seasons");
    }

    public function destroyUnusedData(): void
    {
        // Frontier League는 id 중복값이 있고,
        // GB는 다른 area와 중복 됨.
        // TODO::Frontier League는 어차피 새로 수집안해서 안해도 됨
        RecordTemp::where(['area' => 'GB'])->orWhere(['season' => 'Frontier League'])->delete();
    }

    public function replaceTable(): void
    {
        Schema::rename('record_temps', 'record_temps_bak');
        Schema::rename('records', 'record_temps');
        Schema::rename('record_temps_bak', 'records');
    }

    public function downAvatarImage(): void
    {
        $avatars = Record::select('avatar')->groupBy('avatar')->get()->toArray();
        $avatars = array_column($avatars, 'avatar');
        $avatars = array_filter($avatars);
        // dd($avatars);
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
}
