<?php

namespace Tests\Feature;

use App\models\VideoTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideoTagTest extends TestCase
{
    use RefreshDatabase;

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

    public function testCanDeleteVideoTag()
    {
        $video_tag = factory(VideoTag::class)->create();
        $video_tag_id = $video_tag->id;
        $this->delete(route('video.tags.delete', ['videoTag' => $video_tag_id]))
            ->assertOk()
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);

        $this->assertSoftDeleted('video_tags', [
            'fa_title' => $video_tag->fa_title,
            'en_title' => $video_tag->en_title,
            'status' => $video_tag->status
        ]);

    }

    public function testCanUpdateVideoTag()
    {
        $video_tag = factory(VideoTag::class)->create();
        $data = [
            'fa_title' => 'fa_title',
            'en_title' => 'en_title',
            'status' => 0
        ];
        $this->put(route('video.tags.update', ['videoTag' => $video_tag->id]), $data)
            ->assertOk()
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDatabaseHas('video_tags', $data);
    }
}
