<?php

namespace Tests\Feature;

use App\models\Article;
use App\models\ArticleCategory;
use App\models\ArticleTag;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminArticleTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }


    public function testCanStoreNewArticle()
    {
        $file = UploadedFile::fake()->image('image.jpeg');

        //first parent category
        $parent_article_category = factory(ArticleCategory::class)->create();
//        second parent category
        $second_parent_article_category = factory(ArticleCategory::class)->create(['parent' => $parent_article_category->id]);
        //last level category
        $article_category = factory(ArticleCategory::class)->create(['parent' => $second_parent_article_category->id]);

        $article_tags_ids = factory(ArticleTag::class, 4)->create()
            ->pluck('id')->toArray();

        $writer = factory(User::class)->create();

        $Registrar = factory(User::class)->create();

        $this->actingAs($Registrar);

        $data = [
            'fa_title' => 'article_fa_title',
            'en_title' => 'article_en_title',
            'meta' => 'meta description',
            'text' => 'article_content',
            'short_description' => 'article_short_description',
            'articleCategory_id' => $article_category->id,
            'article_tags' => $article_tags_ids,
            'status' => 1,
            'writer' => $writer->id,
            'file' => $file
        ];
        $this->post(route('articles.store'), $data)
            ->assertStatus(201)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);

        $this->assertDatabaseHas('articles', [
            'fa_title' => 'article_fa_title',
            'en_title' => 'article_en_title',
            'meta' => 'meta description',
            'content' => 'article_content',
            'status' => 1,
            'cover_file_name' => $file->getClientOriginalName(),
            'short_description' => 'article_short_description',
        ]);

        $file_path = storage_path('app/public/images/articles/covers/1/image.jpeg');

        $this->assertFileExists($file_path);

        $article_categories_ids = [$parent_article_category->id, $second_parent_article_category->id, $article_category->id];

        foreach ($article_categories_ids as $id) {

            $this->assertDatabaseHas('article_category', [
                'articleCategory_id' => $id
            ]);
        }

        foreach ($article_tags_ids as $id) {
            $this->assertDatabaseHas('article_tag', [
                'articleTag_id' => $id,
            ]);
        }

    }


    /**
     * @test
     */

    public function testCanUpdateArticle()
    {
        $file = UploadedFile::fake()->image('image.jpeg');

        //first parent category
        $parent_article_category = factory(ArticleCategory::class)->create();
//        second parent category
        $second_parent_article_category = factory(ArticleCategory::class)->create(['parent' => $parent_article_category->id]);
        //last level category
        $article_category = factory(ArticleCategory::class)->create(['parent' => $second_parent_article_category->id]);

        $article_tags_ids = factory(ArticleTag::class, 4)->create()
            ->pluck('id')->toArray();

        $article_id = factory(Article::class)->create(['cover_file_name' => 'mo.txt'])->id;
        $source = fopen(storage_path('app/public/images/articles/covers/' . $article_id . '/mo.txt'), 'w');
        fwrite($source, 'hello mohammad');
        fclose($source);

        $writer = factory(User::class)->create();

        $Registrar = factory(User::class)->create();

        $this->actingAs($Registrar);

        $data = [
            'fa_title' => 'article_fa_title',
            'en_title' => 'article_en_title',
            'meta' => 'meta description',
            'text' => 'article_content',
            'short_description' => 'article_short_description',
            'articleCategory_id' => $article_category->id,
            'article_tags' => $article_tags_ids,
            'status' => 1,
            'writer' => $writer->id,
            'file' => $file
        ];
        $this->put(route('articles.update', ['article' => $article_id]), $data)
            ->assertStatus(200)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);

        $this->assertDatabaseHas('articles', [
            'fa_title' => 'article_fa_title',
            'en_title' => 'article_en_title',
            'meta' => 'meta description',
            'content' => 'article_content',
            'status' => 1,
            'cover_file_name' => $file->getClientOriginalName(),
            'short_description' => 'article_short_description',
        ]);

        $file_path = storage_path('app/public/images/articles/covers/' . $article_id . '/image.jpeg');

        $this->assertFileExists($file_path);

        $article_categories_ids = [$parent_article_category->id, $second_parent_article_category->id, $article_category->id];

        foreach ($article_categories_ids as $id) {

            $this->assertDatabaseHas('article_category', [
                'articleCategory_id' => $id
            ]);
        }

        foreach ($article_tags_ids as $id) {

            $this->assertDatabaseHas('article_tag', [
                'articleTag_id' => $id,
            ]);
        }

    }

    public function testCanSoftDeleteArticle()
    {
        $article = factory(Article::class)->create();

        $this->delete(route('articles.destroy', ['article' => $article->id]))
            ->assertOk();

        $this->assertSoftDeleted('articles', [
            'id' => $article->id
        ]);
    }

    public function testCanSoftDeleteMultipleArticles()
    {
        $articles = factory(Article::class, 10)->create();
        $ids = $articles->pluck('id')->toArray();
        $this->post(route('delete.article.multiple'), [
            'ids' => $ids
        ])
            ->assertOk()
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        foreach ($ids as $id) {
            $this->assertSoftDeleted('articles', [
                'id' => $id
            ]);
        }

    }


    public function testCanRestoreArticle()
    {
        //TODO TEST CAN RESTORE  ARTICLE
    }

    public function testCanForceDeleteArticles()
    {
        $articles = factory(Article::class, 10)->create();
        $ids = $articles->pluck('id');
        $asserting_data = [];
        $this->post(route('article.forceDelete'), [
            'ids' => $ids
        ])
            ->assertOk()
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDeleted('articles', [
            'id' => $articles[0]->id
        ]);
    }

}
