<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRecordsTable extends Migration
{
    private $table = 'records';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->enum('area', ['AS', 'EU', 'GB', 'KR', 'USE', 'USW'])->index('area')->comment('지역');
            $table->string('season')->index('season')->comment('시즌');
            $table->integer('rank')->comment('현재 순위');
            $table->integer('last_rank')->comment('지난 순위');
            $table->integer('gateway_id')->nullable()->comment('모름');
            $table->integer('points')->comment('안쓰는 MMR');
            $table->integer('wins')->comment('승');
            $table->integer('losses')->comment('패');
            $table->integer('disconnects')->comment('디스');
            $table->string('toon')->index('toon')->comment('아이디');
            $table->string('battletag')->nullable()->comment('닉네임');
            $table->string('avatar')->nullable()->comment('아바타 URL');
            $table->string('feature_stat')->nullable()->comment('종족');
            $table->integer('rating')->comment('쓰는 MMR'); // Matchmaking Rating
            $table->enum('bucket', ['1', '2', '3', '4', '5', '6', '7'])->index('bucket')->comment('등급. 1:F ~ 6:A, 7:S');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
        DB::statement("ALTER TABLE `{$this->table}` COMMENT = '전적'");

        // 파티셔닝하려면 primary key여야함.
        $alterPrimarykeySql = "ALTER TABLE `{$this->table}` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`, `season`)";
        DB::statement($alterPrimarykeySql);

        $partitionSql = <<<HEREDOC
ALTER TABLE `{$this->table}`
PARTITION BY LIST COLUMNS(season) (
    PARTITION p_season_2018_1 VALUES IN ('2018 Season 1'),
    PARTITION p_season_2018_2 VALUES IN ('2018 Season 2'),
    PARTITION p_season_2019_1 VALUES IN ('2019 Season 1'),
    PARTITION p_season_2019_2 VALUES IN ('2019 Season 2'),
    PARTITION p_season_2023_1 VALUES IN ('2023 Season 1'),
    PARTITION p_season_2023_2 VALUES IN ('2023 Season 2'),
    PARTITION p_season_2024_1 VALUES IN ('2024 Season 1'),
    PARTITION p_season_2024_2 VALUES IN ('2024 Season 2'),
    PARTITION p_frontier_league VALUES IN ('Frontier League'),
    PARTITION p_season_10 VALUES IN ('Season 10'),
    PARTITION p_season_11 VALUES IN ('Season 11'),
    PARTITION p_season_5 VALUES IN ('Season 5'),
    PARTITION p_season_6 VALUES IN ('Season 6'),
    PARTITION p_season_7 VALUES IN ('Season 7'),
    PARTITION p_season_8 VALUES IN ('Season 8'),
    PARTITION p_season_9 VALUES IN ('Season 9')
)
HEREDOC;
        DB::statement($partitionSql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
