<?php

namespace Tests\Feature;

use App\models\Article;
use App\models\ArticleCategory;
use App\User;
use Tests\TestCase;

class AdminArticleTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function testCanStoreNewArticle()
    {
//        create two article categories for assigning to the article
        $article_category_one = factory(ArticleCategory::class)->create();
        $article_category_two = factory(ArticleCategory::class)->create();
        $ids = [$article_category_one->id, $article_category_two->id];

//        creating the writer of the article
        $writer = factory(User::class)->create();
        $this->actingAs($writer);
        $data = [
            'fa_title' => 'article_fa_title',
            'en_title' => 'article_en_title',
            'meta' => 'meta description',
            'text' => 'article_content',
            'short_description' => 'article_short_description',
            'article_categories' => $ids
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
            'short_description' => 'article_short_description',
        ]);
    }


    /**
     * @test
     */
    public function testCanDeleteArticle()
    {
        $article = factory(Article::class)->create();

        $this->delete(route('articles.destroy', ['article' => $article->id]))
            ->assertOk();

        $this->assertSoftDeleted('articles', [
            'id' => $article->id
        ]);
    }
}
