<?php

namespace Tests\Feature;

use App\models\Course;
use App\models\CourseSection;
use App\models\Video;
use App\models\VideoTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class VideoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function testCanStoreNewCourseVideo()
    {
        $course = factory(Course::class)->create();
        $video_tag_ids = factory(VideoTag::class, 5)->create()->pluck('id')->toArray();
        $courseSection = factory(CourseSection::class)->create(['course_id' => $course->id]);
        $data = [
            'en_title' => 'en_title',
            'fa_title' => 'fa_title',
            'short_description' => 'short_description',
            'description' => 'description',
            "hour" => "30",
            'video_tag_ids' => $video_tag_ids,
            'min' => "40",
            'sec' => "40",
            'status' => 1,
            'meta' => 'meta',
            'is_special_subscription' => 0,
            'courseSection_id' => $courseSection->id,
            'course_id' => $course->id,
            'video_url_name' => 'video url name'
        ];
        $this->post(route('videos.store'), $data)
            ->assertStatus(201)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDatabaseHas('videos', [
            'en_title' => 'en_title',
            'fa_title' => 'fa_title',
            'short_description' => 'short_description',
            'description' => 'description',
            'time' => "30:40:40",
            'is_special_subscription' => 0,
            'courseSection_id' => $courseSection->id,
            'course_id' => $course->id,
        ]);
    }

    public function testCanUploadCourseVideo()
    {
        $course = factory(Course::class)->create();
        $course_section = factory(CourseSection::class)->create(['course_id' => $course->id]);
        $video = factory(Video::class)->create(['is_single_video' => 0, 'courseSection_id' => $course_section->id, 'course_id' => $course->id]);
        $file = UploadedFile::fake()->image('image.jpg');
        $data = [
            'file' => $file
        ];
        $this->post(route('videos.upload', ['video' => $video->id]), $data)
            ->assertStatus(201)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDatabaseHas('videos', [
            'video_url_name' => $file->getClientOriginalName()
        ]);
        $this->assertFileExists(storage_path('app/videos/courses/' . $course->id . '/' . $video->id . '/image.jpg'));
    }

    public function testCanUpdateVideo()
    {
        $video = factory(Video::class)->create();
        $course_id = factory(\App\models\Course::class)->create()->id;
        $video_tags_id = factory(VideoTag::class, 5)->create()->pluck('id')->toArray();
        $course_section_id = factory(\App\models\CourseSection::class, ['course_id' => $course_id])->create()->id;
        $data = [
            'fa_title' => 'fa_title',
            'en_title' => 'en_title',
            'hour' => 20,
            'min' => 20,
            'sec' => 20,
            'is_free' => 1,
            'description' => 'description',
            'is_single_video' => 0,
            'video_tags' => $video_tags_id,
            'is_special_subscription' => 0,
            'courseSection_id' => $course_section_id,
            'course_id' => $course_id,
            'short_description' => 'short_description',
            'meta' => 'meta title',
        ];

        $this->put(route('videos.update', ['video' => $video->id]), $data)
            ->assertOk()
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);

        $this->assertDatabaseHas('videos', [
            'short_description' => 'short_description',
            'meta' => 'meta title',
            'description' => 'description',
            'is_single_video' => 0,
            'is_special_subscription' => 0,
            'courseSection_id' => $course_section_id,
            'course_id' => $course_id,
            'time' => '20:20:20'
        ]);
        $this->assertDatabaseHas('tag_video', [
            'video_id' => $video->id,
            'videoTag_id' => $video_tags_id[0]
        ]);
    }

    public function testDeleteVideo()
    {
        $course = factory(Course::class)->create();
        $video = factory(Video::class)->create(['course_id' => $course->id]);

        $this->delete(route('videos.destroy', ['video' => $video->id]))
            ->assertOk()
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertSoftDeleted('videos', [
            'id' => $video->id
        ]);
    }

    public function testCanDeleteMultiVideos()
    {
        $videos = factory(Video::class, 10)->create();
        $ids = $videos->pluck('id')->toArray();
        $this->post(route('videos.delete.multi'), ['ids' => $ids])
            ->assertOk();

        foreach ($ids as $id) {
            $this->assertSoftDeleted('videos', [
                'id' => $id
            ]);
        }
    }

    public function testCanForceDeleteVideo()
    {
        //TODO TEST CAN FORCE DELETE VIDEO
    }

    public function testCanRestoreVideo()
    {
        $videos = factory(Video::class, 10)->create();
        foreach ($videos as $item) {
            $item->delete();
        }
        $ids = $videos->pluck('id')->toArray();

        $this->post(route('videos.restore'), ['ids' => $ids])
            ->assertOk();

        foreach ($ids as $id) {
            $this->assertDatabaseHas('videos', [
                'id' => $id
            ]);
        }


    }

}
