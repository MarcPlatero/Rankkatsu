<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsSuspiciousToRankingsAndOptions extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rankings', function (Blueprint $table) {
            $table->boolean('is_suspicious')->default(false)->after('image');
        });

        Schema::table('ranking_options', function (Blueprint $table) {
            $table->boolean('is_suspicious')->default(false)->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rankings', function (Blueprint $table) {
            $table->dropColumn('is_suspicious');
        });

        Schema::table('ranking_options', function (Blueprint $table) {
            $table->dropColumn('is_suspicious');
        });
    }
};
