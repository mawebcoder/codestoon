<?php

namespace Tests\Feature;

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
}
