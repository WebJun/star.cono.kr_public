<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRepliesTable extends Migration
{
    private $table = 'replies';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('toon')->index('toon')->comment('toon');
            $table->string('area')->index('area')->comment('지역');
            $table->string('nickname')->nullable();
            $table->text('content')->nullable();
            $table->text('password')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
        DB::statement("ALTER TABLE `{$this->table}` COMMENT = '댓글'");
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
