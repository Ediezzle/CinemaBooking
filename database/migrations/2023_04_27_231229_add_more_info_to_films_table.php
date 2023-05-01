<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('films', function (Blueprint $table) {
            $table->string('starring', 300)->after('year');
            $table->string('director', 100)->after('starring');
            $table->string('genre', 100)->after('director');
            $table->string('cover_photo', 100)->after('genre')->nullable();
            $table->string('duration', 10)->after('cover_photo');
            $table->smallInteger('rating')->after('duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('films', function (Blueprint $table) {
            $table->dropColumn('rating');
            $table->dropColumn('duration');
            $table->dropColumn('cover_photo');
            $table->dropColumn('starring');
            $table->dropColumn('director');
            $table->dropColumn('starring');

        });
    }
};
