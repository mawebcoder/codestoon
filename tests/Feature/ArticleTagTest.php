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
            'en_title'=>Str::random(20),
            'status'=>1,
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
        $data = [
            'fa_title' => $fa_title
        ];
        $this->put(route('articles.tag.update', ['articleTag' => $old_article_tag->id]), $data)
            ->assertOk()
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDatabaseHas('article_tags', $data);
    }

    public function testCanDestroyArticleTag()
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

    public function testCanForceDeletedArticleTag()
    {

        //TODO TEST CAN FORCE DELETE A ARTICLE TAG
    }

    public function testCanRestoreArticleTag()
    {
        //TODO TEST CAN RESTORE ARTICLE TAG
    }

}
