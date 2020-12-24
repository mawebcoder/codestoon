<?php

namespace Tests\Feature;

use App\models\ArticleTag;
use Illuminate\Support\Str;
use Tests\TestCase;

class ArticleTagTest extends TestCase
{
    /**
     * @test
     */
    public function testCanStoreArticleTag()
    {
        $data = [
            'fa_title' => Str::random(20)
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

}
