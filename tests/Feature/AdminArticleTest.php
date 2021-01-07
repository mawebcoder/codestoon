<?php

namespace Tests\Feature;

use App\models\Article;
use App\models\ArticleCategory;
use App\models\ArticleTag;
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
        $article_tags = factory(ArticleTag::class, 4)->create();
        $article_tags_ids = $article_tags->pluck('id');
//        creating the writer of the article
        $writer = factory(User::class)->create();
        $this->actingAs($writer);
        $data = [
            'fa_title' => 'article_fa_title',
            'en_title' => 'article_en_title',
            'meta' => 'meta description',
            'text' => 'article_content',
            'short_description' => 'article_short_description',
            'article_categories' => $ids,
            'article_tags' => $article_tags_ids
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
        $this->assertDatabaseHas('article_category', [
            'articleCategory_id' => $ids[0],
        ]);
        $this->assertDatabaseHas('article_tag', [
            'articleTag_id' => $article_tags_ids[0],
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

    public function testCanUpdateArticle()
    {
        $article_category_one = factory(ArticleCategory::class)->create();
        $article_category_two = factory(ArticleCategory::class)->create();
        $ids = [$article_category_one->id, $article_category_two->id];
        $article_tags_ids = factory(ArticleTag::class, 5)->create()->pluck('id');
        $article = factory(Article::class)->create();
//        creating the writer of the article
        $writer = factory(User::class)->create();
        $this->actingAs($writer);
        $data = [
            'fa_title' => 'article_fa_title',
            'en_title' => 'article_en_title',
            'meta' => 'meta description',
            'text' => 'article_content',
            'short_description' => 'article_short_description',
            'article_categories' => $ids,
            'article_tags' => $article_tags_ids
        ];
        $this->put(route('articles.update', ['article' => $article->id]), $data)
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
            'short_description' => 'article_short_description',
        ]);
        $this->assertDatabaseHas('article_category', [
            'articleCategory_id' => $ids[0],
        ]);
        $this->assertDatabaseHas('article_tag', [
            'articleTag_id' => $article_tags_ids[0]
        ]);

    }

    public function testCanDeleteMultipleArticles()
    {
        $articles = factory(Article::class, 10)->create();
        $ids = $articles->pluck('id');
        $this->post(route('delete.article.multiple'), [
            'ids' => $ids
        ])
            ->assertOk()
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertSoftDeleted('articles', [
            'id' => $articles[0]->id
        ]);
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
