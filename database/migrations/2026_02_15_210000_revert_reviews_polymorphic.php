<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Reverse the polymorphic reviews table to separate place_id and ecohotel_id.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('reviews', function (Blueprint $table) {
                // simple version for sqlite
                $table->dropUnique('unique_user_reviewable');
                $table->dropIndex(['reviewable_id', 'reviewable_type']);
                $table->dropColumn(['reviewable_id', 'reviewable_type']);
                
                $table->unsignedBigInteger('place_id')->nullable()->after('user_id');
                $table->unsignedBigInteger('ecohotel_id')->nullable()->after('place_id');
                
                $table->unique(['user_id', 'place_id'], 'reviews_user_id_place_id_unique');
                $table->unique(['user_id', 'ecohotel_id'], 'reviews_user_id_ecohotel_id_unique');
            });
            return;
        }

        $database = DB::getDatabaseName();

        $indexExists = function (string $indexName) use ($database): bool {
            $result = DB::select(
                'SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ? LIMIT 1',
                [$database, 'reviews', $indexName]
            );

            return ! empty($result);
        };

        $foreignKeyExists = function (string $foreignKeyName) use ($database): bool {
            $result = DB::select(
                "SELECT 1 FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'reviews' AND CONSTRAINT_TYPE = 'FOREIGN KEY' AND CONSTRAINT_NAME = ? LIMIT 1",
                [$database, $foreignKeyName]
            );

            return ! empty($result);
        };

        // Eliminar todos los constraints llamados unique_user_reviewable (pueden ser duplicados por errores previos)
        // Intentar eliminar cualquier foreign key que dependa de unique_user_reviewable (por si acaso)
        $fkConstraints = DB::select(
            "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'reviews' AND CONSTRAINT_NAME != 'PRIMARY' AND REFERENCED_TABLE_NAME IS NOT NULL",
            [$database]
        );
        foreach ($fkConstraints as $fk) {
            DB::statement("ALTER TABLE reviews DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }
        // Eliminar el índice unique_user_reviewable
        $constraints = DB::select(
            "SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'reviews' AND CONSTRAINT_NAME = 'unique_user_reviewable'",
            [$database]
        );
        foreach ($constraints as $c) {
            DB::statement("ALTER TABLE reviews DROP INDEX `{$c->CONSTRAINT_NAME}`");
        }

        if ($indexExists('reviews_user_id_place_id_unique')) {
            DB::statement('ALTER TABLE reviews DROP INDEX `reviews_user_id_place_id_unique`');
        }
        if ($indexExists('reviews_user_id_ecohotel_id_unique')) {
            DB::statement('ALTER TABLE reviews DROP INDEX `reviews_user_id_ecohotel_id_unique`');
        }
        if ($indexExists('reviews_reviewable_id_reviewable_type_index')) {
            DB::statement('ALTER TABLE reviews DROP INDEX `reviews_reviewable_id_reviewable_type_index`');
        }

        // Ahora usar Blueprint para el resto
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'reviewable_id')) $table->dropColumn('reviewable_id');
            if (Schema::hasColumn('reviews', 'reviewable_type')) $table->dropColumn('reviewable_type');

            if (! Schema::hasColumn('reviews', 'place_id')) {
                $table->unsignedBigInteger('place_id')->nullable()->after('user_id');
            }

            if (! Schema::hasColumn('reviews', 'ecohotel_id')) {
                $table->unsignedBigInteger('ecohotel_id')->nullable()->after('place_id');
            }
        });

        Schema::table('reviews', function (Blueprint $table) use ($foreignKeyExists) {
            if (Schema::hasColumn('reviews', 'place_id') && ! $foreignKeyExists('reviews_place_id_foreign')) {
                $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
            }

            if (Schema::hasColumn('reviews', 'ecohotel_id') && ! $foreignKeyExists('reviews_ecohotel_id_foreign')) {
                $table->foreign('ecohotel_id')->references('id')->on('ecohotels')->onDelete('cascade');
            }
        });

        if (! $indexExists('reviews_user_id_place_id_unique')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->unique(['user_id', 'place_id'], 'reviews_user_id_place_id_unique');
            });
        }

        if (! $indexExists('reviews_user_id_ecohotel_id_unique')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->unique(['user_id', 'ecohotel_id'], 'reviews_user_id_ecohotel_id_unique');
            });
        }
    }

    /**
     * Revert the changes (volver a polimórfico si se hace rollback).
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique('reviews_user_id_place_id_unique');
            $table->dropUnique('reviews_user_id_ecohotel_id_unique');
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['place_id']);
                $table->dropForeign(['ecohotel_id']);
            }
            $table->dropColumn(['place_id', 'ecohotel_id']);
            $table->unsignedBigInteger('reviewable_id')->nullable();
            $table->string('reviewable_type')->nullable();
            $table->index(['reviewable_id', 'reviewable_type']);
            $table->unique(['user_id', 'reviewable_id', 'reviewable_type'], 'unique_user_reviewable');
        });
    }
};
