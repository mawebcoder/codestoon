<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('fa_title')->nullable();
            $table->longText('content');
            $table->string('en_title')->nullable();
            $table->boolean('status')->default(0);
            $table->text('short_description');
            $table->string('meta');
            $table->unsignedBigInteger('articleCategory_id')->nullable();
            $table->foreign('articleCategory_id')->references('id')->on('article_categories')->onDelete(null);
            $table->string('cover_file_name')->nullable();
            $table->text('likes')->nullable();
            $table->string('slug')->nullable();
            $table->softDeletes();
            $table->longText('hint')->nullable(null);
            $table->unsignedBigInteger('writer')->nullable();
            $table->foreign('writer')->references('id')->on('users')->onDelete(null);
            $table->unsignedBigInteger('Registrar')->nullable();
            $table->foreign('Registrar')->references('id')->on('users')->onDelete(null);
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
        Schema::dropIfExists('articles');
    }
}
