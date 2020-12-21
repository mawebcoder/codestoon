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
        $this->post(route('articleCategories.store'), $data)
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
        $category = factory(ArticleCategory::class, 1)->create()->first();
        $this->delete(route('articleCategories.destroy',['articleCategory'=>$category->id]))
            ->assertStatus(200)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertSoftDeleted('article_categories', [
            'fa_title' => $category->fa_title,
            'en_title' => $category->en_title,
            'description' => $category->description,
        ]);

    }
}
