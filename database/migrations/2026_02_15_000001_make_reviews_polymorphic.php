<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['place_id']);
            $table->dropUnique(['user_id', 'place_id']);
            $table->dropColumn('place_id');
            $table->unsignedBigInteger('reviewable_id')->after('user_id');
            $table->string('reviewable_type')->after('reviewable_id');
            $table->index(['reviewable_id', 'reviewable_type']);
            $table->unique(['user_id', 'reviewable_id', 'reviewable_type'], 'unique_user_reviewable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique('unique_user_reviewable');
            $table->dropIndex(['reviewable_id', 'reviewable_type']);
            $table->dropColumn(['reviewable_id', 'reviewable_type']);
            $table->unsignedBigInteger('place_id')->nullable();
            if (DB::getDriverName() !== 'sqlite') {
                $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
            }
            $table->unique(['user_id', 'place_id']);
        });
    }
};
