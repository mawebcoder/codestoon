<?php

namespace Tests\Feature;

use Tests\TestCase;

class VideoTagTest extends TestCase
{
    public function testCanStoreVideoTag()
    {
        $data = [
            'fa_title' => 'fa_title',
            'en_title' => 'en_title',
            'status' => 0
        ];
        $this->post(route('video.tags.store'), $data)
            ->assertStatus(201)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDatabaseHas('video_tags', $data);
    }
}
