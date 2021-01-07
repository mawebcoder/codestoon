<?php

namespace Tests\Feature;

use App\models\ArticleCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminArticleCategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

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
        $this->post(route('article.category.store'), $data)
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
        $this->delete(route('article.category.destroy', ['articleCategory' => $category->id]))
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

    /**
     * @test
     */
    public function testCanUpdateArticleCategory()
    {
        $article_category = factory(ArticleCategory::class, 1)->create();

        $data = [
            'fa_title' => 'persiantitle',
            'en_title' => 'englishtitle',
            'description' => 'newdescription'
        ];
        $this->put(route('article.category.update', ['articleCategory' => $article_category[0]['id']]), $data)
            ->assertStatus(200)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDatabaseHas('article_categories', $data);
    }

    public function testCanSoftDeleteArticleCategory()
    {
        //TODO TEST CAN SOFT DELETE ARTICLE CATEGORY
    }

    public function testCanRestoreArticleCategory()
    {
        //TODO TEST CAN RESTORE ARTICLE CATEGORY
    }

}
