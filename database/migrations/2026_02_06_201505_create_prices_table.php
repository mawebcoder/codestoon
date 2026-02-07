<?php

declare(strict_types=1);

use App\Models\Price;
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
        Schema::create(Price::getTableName(), static function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 20, 4);
            $table->decimal('compare_at_price', 20, 4);
            $table->string('priceable_type');
            $table->unsignedInteger('priceable_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Price::getTableName());
    }
};
