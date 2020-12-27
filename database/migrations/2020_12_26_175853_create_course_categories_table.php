<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_categories', function (Blueprint $table) {
            $table->id();
            $table->string('fa_title');
            $table->string('en_title')->nullable();
            $table->string('meta')->nullable();
            $table->longText('description');
            $table->text('short_description')->nullable();
            $table->softDeletes();
            $table->unsignedBigInteger('parent')->default(0);
            $table->string('course_image_cover_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_categories');
    }
}
