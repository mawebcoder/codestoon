<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('fa_title');
            $table->string('en_title')->nullable();
            $table->string('time');
            $table->boolean('is_free')->default(0);
            $table->text('description');
            $table->boolean('is_single_video')->default(0);
            $table->boolean('is_special_subscription');
            $table->unsignedBigInteger('courseSection_id')->nullable();
            $table->foreign('courseSection_id')->references('id')
                ->on('course_sections')->onDelete(null);
            $table->unsignedBigInteger('course_id')->nullable();
            $table->foreign('course_id')->references('id')
                ->on('courses')->onDelete(null);
            $table->text('short_description')->nullable();
            $table->string('meta');
            $table->bigInteger('hint')->default(0);
            $table->string('likes')->nullable();
            $table->string('video_url_name')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('videos');
    }
}
