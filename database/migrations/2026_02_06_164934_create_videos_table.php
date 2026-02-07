<?php

declare(strict_types=1);

use App\Models\Tutorial;
use App\Models\Video;
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
        Schema::create(Video::getTableName(), static function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->boolean('enabled')->default(false);
            $table->text('description');
            $table->string('slug')->index();
            $table->tinyInteger('time_in_hours')->default(0);
            $table->tinyInteger('time_in_minutes')->default(0);
            $table->tinyInteger('time_in_seconds')->default(0);
            $table->foreignId('tutorial_id')->constrained(Tutorial::getTableName())
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
