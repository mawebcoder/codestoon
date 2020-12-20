<?php

namespace Tests\Feature;

use App\models\ArticleCategory;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminArticleCategoryTest extends TestCase
{
    /**
     * @test
     */
    public function testCanStoreNewArticleCategory()
    {
        $fa_title = Str::random(10);
        $en_title = Str::random(10);
        $description = Str::random(100);
        $data = [
            'fa_title' => $fa_title,
            'en_title' => $en_title,
            'description' => $description
        ];
        $this->post(route('categories.store'), $data)
            ->assertStatus(201)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDatabaseHas('article_categories', $data);
    }

    /**
     * @test
     */
    public function testCanDeleteArticleCategory()
    {
        $article = factory(ArticleCategory::class, 1)->create();

        $this->delete(route('categories.destroy'), ['articleCategory' => $article->id])
            ->assertStatus(200)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDeleted('article_categories', [
            'fa_title' => $article->fa_title,
            'en_title' => $article->en_title,
            'description' => $article->description
        ]);

    }
}
