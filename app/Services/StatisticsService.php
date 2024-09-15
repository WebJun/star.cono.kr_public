<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Record;

class StatisticsService
{
    /**
     * @return array
     */
    public function getKindCounts(): array
    {
        $kindCounts = $this->fetchKindCounts();
        $kindCounts = $this->transformKindCounts($kindCounts);
        $kindCounts = $this->addMissingKindType($kindCounts);
        return $kindCounts;
    }

    /**
     * @return array
     */
    private function fetchKindCounts(): array
    {
        return DB::table('records')
            ->select(DB::raw('feature_stat, COUNT(*) as cnt'))
            ->where('season', '2024 Season 2')
            ->groupBy('feature_stat')
            ->get()->toArray();
    }

    /**
     * @param array $kindCounts
     * @return array
     */
    private function transformKindCounts(array $kindCounts): array
    {
        foreach ($kindCounts as &$kindCount) {
            $kindCount = (array)$kindCount;
            switch ($kindCount['feature_stat']) {
                case 'protoss':
                    $kindCount['kind'] = 'P';
                    break;
                case 'terran':
                    $kindCount['kind'] = 'T';
                    break;
                case 'zerg':
                    $kindCount['kind'] = 'Z';
                    break;
                case '':
                    $kindCount['feature_stat'] = '-';
                    $kindCount['kind'] = '-';
                    break;
            }
        }
        return $kindCounts;
    }

    /**
     * @param array $kindCounts
     * @return array
     */
    private function addMissingKindType(array $kindCounts): array
    {
        if (count($kindCounts) === 3) {
            $kindCounts[] = [
                'feature_stat' => '-',
                'cnt' => 0,
                'kind' => '-',
            ];
        }
        return $kindCounts;
    }

    /**
     * @return array
     */
    public function getAbilityKindDistribution(): array
    {
        $records = $this->fetchRecords();
        $recordCnt = count($records);
        $kindChunks = $this->chunkKindTypes($records, ceil($recordCnt / 50));
        $result = $this->calculateKindDistribution($kindChunks);
        return $result;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function fetchRecords()
    {
        return Record::select('feature_stat')
            ->where('season', '2024 Season 2')
            ->where('area', 'KR')
            ->whereNotNull('feature_stat')
            ->orderBy('rank', 'DESC')->get();
    }

    /**
     * @param \Illuminate\Support\Collection $records
     * @param int $chunkSize
     * @return array
     */
    private function chunkKindTypes($records, int $chunkSize): array
    {
        $kindTypes = $records->pluck('feature_stat')->toArray();
        $kindChunks = array_chunk($kindTypes, $chunkSize);
        array_pop($kindChunks); // Remove the last chunk if it's incomplete
        return $kindChunks;
    }

    /**
     * @param array $kindChunks
     * @return array
     */
    private function calculateKindDistribution(array $kindChunks): array
    {
        $result = ['P' => [], 'Z' => [], 'T' => []];
        foreach ($kindChunks as $kindChunk) {
            $result['P'][] = $this->calculateKindPercentage($kindChunk, 'protoss');
            $result['Z'][] = $this->calculateKindPercentage($kindChunk, 'zerg');
            $result['T'][] = $this->calculateKindPercentage($kindChunk, 'terran');
        }
        return $result;
    }

    /**
     * @param array $kindChunk
     * @param string $kindType
     * @return float|int
     */
    private function calculateKindPercentage(array $kindChunk, string $kindType)
    {
        return floor(count(array_filter($kindChunk, function ($kind) use ($kindType) {
            return $kind === $kindType;
        })) / (count($kindChunk) / 100));
    }
}
