<?php

namespace Tests\Feature;

use App\models\ArticleCategory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminArticleCategoryTest extends TestCase
{
//    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCanStoreNewArticleCategory()
    {
        $file = UploadedFile::fake()->image('image.jpeg', 1000, 100);
        $article_category = factory(ArticleCategory::class)->create();
        $fa_title = Str::random(10);
        $en_title = Str::random(10);
        $description = Str::random(100);
        $data = [
            'fa_title' => $fa_title,
            'en_title' => $en_title,
            'description' => $description,
            'parent' => $article_category->id,
            'status' => 1,
            'file' => $file
        ];
        $this->post(route('article.category.store'), $data)
            ->assertStatus(201)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDatabaseHas('article_categories', [
            'fa_title' => $fa_title,
            'en_title' => $en_title,
            'description' => $description,
            'parent' => $article_category->id,
            'status' => 1
        ]);

        $this->assertFileExists(storage_path('app/public/images/articles/categories/2/' . $file->getClientOriginalName()));
    }

    public function testCanSoftDeleteArticleCategory()
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

    public function testCanUpdateArticleCategory()
    {
        $file = UploadedFile::fake()->image('image.jpeg');
        $new_parent = factory(ArticleCategory::class)->create();
        $article_category = factory(ArticleCategory::class)->create();
        $old_cover_file = CreateFakeFile(storage_path('app/public/images/articles/categories/2/' . $file->getClientOriginalName()));
        $data = [
            'fa_title' => 'persiantitle',
            'en_title' => 'englishtitle',
            'description' => 'newdescription',
            'parent' => $new_parent->id,
            'status' => 1,
            'file' => $file
        ];
        $this->post(route('article.category.update', ['articleCategory' => $article_category->id]), $data)
            ->assertStatus(200)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);

        $this->assertDatabaseHas('article_categories', [
            'fa_title' => 'persiantitle',
            'en_title' => 'englishtitle',
            'description' => 'newdescription',
            'parent' => $new_parent->id,
            'status' => 1,
            'cover_file_name' => $file->getClientOriginalName()
        ]);

        $this->assertFileExists(storage_path('app/public/images/articles/categories/2/' . $file->getClientOriginalName()));
    }

    public function testCanSoftDeleteMultipleArticleCategory()
    {
        $articles_categories_ids = factory(ArticleCategory::class, 4)->create()->pluck('id')
            ->toArray();
        $response = $this->post(route('delete.multiple.article.category'), ['ids' => $articles_categories_ids])
            ->assertStatus(200)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        foreach ($articles_categories_ids as $id){
            $this->assertSoftDeleted('article_categories', [
                'id' => $id
            ]);
        }

    }

    public function testCanForceDeleteMultipleArticleCategory()
    {
        $article_categories = factory(ArticleCategory::class, 10)->create(['cover_file_name' => 'mo.txt']);
        $article_ids = $article_categories->pluck('id')->toArray();

        foreach ($article_ids as $id) {
            Storage::disk('public')->put('images/articles/categories/' . $id . '/mo.txt', 'hello mohamamd');
        }

        $this->post(route('articles.categories.force.delete'), ['ids' => $article_ids])
            ->assertOk();

        foreach ($article_ids as $id) {
            $this->assertDatabaseMissing('article_categories', [
                'id' => $id
            ]);
        }

        foreach ($article_ids as $id) {

            $this->assertFileDoesNotExist(storage_path('app/public/images/articles/categories/' . $id . '/mo.txt'));
        }

    }

    public function testCanRestoreMultipleArticleCategories()
    {
        $articles = factory(ArticleCategory::class, 10)->create();
        $ids = $articles->pluck('id')->toArray();
        foreach ($articles as $article) {
            $article->delete();
        }

        $this->post(route('articles.categories.restore'), ['ids' => $ids])
            ->assertOk();

        foreach ($ids as $id) {
            $this->assertDatabaseHas('article_categories', [
                'id' => $id
            ]);
        }

    }

}
