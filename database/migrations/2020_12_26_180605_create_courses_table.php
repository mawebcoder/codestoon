<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('fa_title');
            $table->string('en_title')->nullable();
            $table->string('time')->nullable();
            $table->decimal('price', 20)->default(0);
            $table->boolean('has_discount')->default(0);
            $table->float('discount_value', 10)->default(0);
            $table->enum('level', ['beginner', 'medium', 'advanced'])->default('beginner');
            $table->unsignedBigInteger('course_teacher');
            $table->boolean('is_special_subscription')->default(0);
            $table->string('course_image_cover')->nullable();
            $table->text('likes')->nullable();
            $table->bigInteger('hint')->default(0);
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->boolean('is_completed_course')->default(0);
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
        Schema::dropIfExists('courses');
    }
}
