<?php

namespace Tests\Feature;

use App\models\ArticleTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ArticleTagTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function testCanStoreArticleTag()
    {
        $data = [
            'fa_title' => Str::random(20),
            'en_title' => Str::random(20),
            'status' => 1,
        ];
        $this->post(route('article.tag.store'), $data)
            ->assertStatus(201)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDatabaseHas('article_tags', $data);
    }

    public function testCanUpdateArticleTag()
    {
        $old_article_tag = factory(ArticleTag::class)->create();
        $fa_title = Str::random(10);
        $en_title = Str::random(10);
        $data = [
            'fa_title' => $fa_title,
            'en_title' => $en_title,
            'status' => 1
        ];
        $this->put(route('articles.tag.update', ['articleTag' => $old_article_tag->id]), $data)
            ->assertOk()
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDatabaseHas('article_tags', $data);
    }

    public function testCanSoftDeleteArticleTag()
    {
        $article = factory(ArticleTag::class)->create();

        $this->delete(route('article.tag.destroy', ['articleTag' => $article->id]))
            ->assertOk()
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertSoftDeleted('article_tags', [
            'fa_title' => $article->fa_title
        ]);
    }

    public function testCanSoftDeleteMultipleArticleTag()
    {
        $data = factory(ArticleTag::class, 4)->create()->pluck('id')
            ->toArray();
        $this->post(route('article.tags.delete.multiple'), ['ids' => $data])
            ->assertStatus(200)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertSoftDeleted('article_tags', [
            'id' => $data[0]
        ]);
    }

    public function testCanForceDeletedArticleTag()
    {
        $articles_tags_id = factory(ArticleTag::class, 10)->create()
            ->pluck('id')->toArray();

        $this->post(route('articles.tags.force.delete'), ['ids' => $articles_tags_id])
            ->assertStatus(200)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        foreach ($articles_tags_id as $item) {
            $this->assertDatabaseMissing('article_tags', [
                'id' => $item
            ]);
        }
    }

    public function testCanRestoreArticleTag()
    {
        //TODO TEST CAN RESTORE ARTICLE TAG
    }

}
