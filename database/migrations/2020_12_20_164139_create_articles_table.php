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
            $table->unsignedBigInteger('articleCategory_id');
            $table->foreign('articleCategory_id')->references('id')->on('article_categories')->onDelete(null);
            $table->string('cover_file_name')->nullable();
            $table->text('likes')->nullable();
            $table->string('slug')->nullable();
            $table->softDeletes();
            $table->bigInteger('hint')->default(0);
            $table->foreignId('writer')->references('id')->on('users')->onDelete(null);
            $table->foreignId('Registrar')->references('id')->on('users')->onDelete(null);
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
